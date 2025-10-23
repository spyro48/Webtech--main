const mysql = require("mysql2/promise");
const { config } = require('./config');

console.log("Creating connection pool...")

const pool = mysql.createPool(config.MYSQL);
module.exports = pool;