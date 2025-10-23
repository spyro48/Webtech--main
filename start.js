const express = require("express");
const fs = require('fs');
const http = require("http");
const socket = require("socket.io");
const session = require("express-session");
const cookieParser = require("cookie-parser");
const cors = require('cors');
const getVideoDurationInSeconds = require('video-duration');


const { config } = require('./config');
const { 
  delete_user, 
  get_user_by_id, 
  add_new_user, 
  edit_user, 
  get_users, 
  get_user, 
  toggle_user_status,
  change_user_password
} = require('./model/users');
const {
  get_video_by_id,
  get_video_by_schedule
} = require('./model/videos');
const {
  get_schedule_now
} = require('./model/schedule');

const options = {
  cors: {
    origin: '*',
  }
};
const app = express();
const httpServer = http.createServer(app);
const io = socket(httpServer, options);


app.set('view engine', 'ejs')

app.use(cors());
app.use(express.static('public'))
app.use(express.json());
app.use(cookieParser());
app.use(session({
  secret: "s3cr3t",
  saveUninitialized: true,
  resave: true
}));
app.use(express.urlencoded({extended: true}));

app.get('/', (req, res) => {
  if (req.session.uid) {
    if (req.session.role === "admin") {
      res.redirect('/admin');
    } else {
      res.redirect(`http://${req.hostname}${config.MANAGER.endpoint}`);
    }
  } else {
    res.render('pages/index');
  }
});


app.post('/login', (req, res) => {
  const { username, password } = req.body;

  var message = { 
    success: false, 
    message: "Incorrect username or password.",
    endpoint: ""
  }

  get_user(username, password)
  .then(function(results){
    if (results && results.length > 0) {
      const row = results[0];

      if (row['status'] != 1) {
        message['message'] = 'Account is disabled. Please check with your administrator.';
        return res.status(401).send(message);
      } 

      message['success'] = true;
      message['message'] = 'Login successful';
      req.session.authenticated = true;
      req.session.role = row.usertype;
      req.session.uid = row.id;

      if (row.usertype === 'user') {
        message['endpoint'] = `http://${req.hostname}${config.MANAGER.endpoint}?uid=${req.session.uid}`;
        return res.status(401).send(message);
      } else {
        message['endpoint'] = `http://${req.hostname}:${config.APP.port}/admin`;
        return res.status(401).send(message);
      }
      
    } else {
      return res.status(401).send(message);
    }

  })
  .catch(function(err){
    console.log(err);
    return res.status(500).send({ success: false, message: "Error executing the query!!" });
  })
});

app.get('/logout', (req, res) => {
  console.log(`Current Role: ${req.session.role}`);
  req.session.destroy();
  console.log('Session destroyed.')
  res.redirect("/");
});

app.get('/admin', (req, res) => {
  if (req.session.uid) {
    if (req.session.role === 'admin') {
      get_users()
      .then(function(results){
        res.render('pages/admin', {data: results});
      })
      .catch(function(err){
        return res.status(500).send({ success: false, message: 'Error executing query.' });
      })
    } else {
      res.redirect(`http://${req.hostname}${manager_endpoint}`)

    }   

  } else {
    res.redirect('/');
  }

  
});


// USERS
app.post('/add_user' , (req, res) => {
  const {firstName, lastName, userType, username, password} = req.body

  add_new_user([firstName, lastName, username, password, userType])
  .then(function(results){
    res.redirect('/admin');
  })
  .catch(function(err){
    console.log("Error: "+err);
  });

});

app.get('/user/:id', (req, res) => {

  get_user_by_id(req.params.id)
  .then(function(results){
    res.json(results[0]);
  })
  .catch(function(err){
    console.log("Error: "+err);
    return res.status(500).send({ success: false, message: 'Error executing query.' });
  });
  
});

app.post('/edit_user' , (req, res) => {
  const {firstName, lastName, userType, username, userid} = req.body

  edit_user([firstName, lastName, username, userType, userid])
  .then(function(results){
    res.redirect('/admin');
  })
  .catch(function(err){
      console.log("Error: "+err);
  })
});

app.post('/delete_user' , (req, res) => {
  const {userid} = req.body

  delete_user(userid)
  .then(function(results){
    res.redirect('/admin');
  })
  .catch(function(err){
      console.log("Error: "+err);
  })  
});

app.post('/toggle_user_status' , (req, res) => {
  const {userid, status} = req.body

  toggle_user_status(userid, status)
  .then(function(results){
    res.redirect('/admin');
  })
  .catch(function(err){
      console.log("Error: "+err);
  })  
});

app.post('/change_password' , (req, res) => {
  const {changeuserid, new_password} = req.body

  console.log(changeuserid, new_password);

  change_user_password(changeuserid, new_password)
  .then(function(results){
    res.redirect('/admin');
  })
  .catch(function(err){
      console.log(err);
  })
});

app.get('/fetchvideo', (req, res) => {
  get_schedule_now()
  .then(function(results){

      if (results.length > 0) {
        const schedule = results[0];

        get_video_by_schedule(schedule.schedule_id)
        .then(function(videos){
            return res.json({
              "schedule": schedule,
              "videos": videos
            });
        })
        .catch(function(err){
            console.log("Error: "+err);
        })

       

      } else {
        console.log("No scheduled videos at the current time.")

        return res.json({});
      }
     
  })
  .catch(function(err){
      console.log("Error: "+err);
  })
});
app.get('/video/:id', (req, res) => {
  const videoID = req.params.id;

  get_video_by_id(videoID)
  .then(function(results){
      if (results.length === 0) {
        res.status(404).send('Video not found');
        return;
      }

      const videoPath = results[0].file_path;
      const stat = fs.statSync(videoPath);
      const fileSize = stat.size;
      const range = req.headers.range;

      if (range) {
        const parts = range.replace(/bytes=/, '').split('-');
        const start = parseInt(parts[0], 10);
        const end = parts[1] ? parseInt(parts[1], 10) : fileSize - 1;
    
        const chunksize = (end - start) + 1;
        const file = fs.createReadStream(videoPath, { start, end });
        const head = {
          'Content-Range': `bytes ${start}-${end}/${fileSize}`,
          'Accept-Ranges': 'bytes',
          'Content-Length': chunksize,
          'Content-Type': 'video/mp4',
        };
    
        res.writeHead(206, head);
        file.pipe(res);
      } else {
        const head = {
          'Content-Length': fileSize,
          'Content-Type': 'video/mp4',
        };
        res.writeHead(200, head);
        var readStream = fs.createReadStream(videoPath).pipe(res);
      }

  })
  .catch(function(err){
    console.error('Error fetching video', err);
    return res.status(500).send({ success: false, message: 'Error executing query.' });
  })

});

// io.on('connection', (socket) => {
//   console.log('A user connected');

//   // Handle video stream
//   socket.on('stream', (image) => {
//     // Broadcast the video stream to all connected clients
//     io.emit('stream', image);
//   });

//   socket.on('disconnect', () => {
//     console.log('User disconnected');
//   });
// });




httpServer.listen(config.APP.port, () => {
  console.log(`App listening at http://localhost:${config.APP.port}`);
});