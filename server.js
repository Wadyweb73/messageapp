var socketIO = require('socket.io');
var express  = require('express');
var https    = require('https');
var http     = require('http');
var winston  = require('winston');

const port  = 3000;
const logger = winston.createLogger({
    level: 'info',
    format: winston.format.combine(
        winston.format.colorize(),
        winston.format.timestamp({ format: 'YYYY-MM-DD HH:mm:ss' }),
        winston.format.printf(info => `[${info.timestamp}]: ${info.message}`)
    ),
    transports: [
        new winston.transports.Console()
    ]
});

logger.info('SocketIO > Listening on port '+port);

/*
 * *
logger.remove(logger.transports.Console);
logger.add(logger.transports.Console, {colorize: true, timestamp: 'true'});
logger.info(`SocketIO > listening on port ${port}`);
 * *
 */

var app     = express();
var http_server = http.createServer(app).listen(port);

function emitNewOrder( http_server ) {
    var io = socketIO(http_server); 
    var ev = 'new_order';

    io.sockets.on('connection', (socket) => {
        logger.info('New Socket Connected!') ;

        socket.on(ev, (data) => {
            io.emit(ev, data); 
        })
    })
}

emitNewOrder(http_server);
