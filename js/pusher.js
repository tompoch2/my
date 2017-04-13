//var sys = require('sys');
//var net = require('net');
//var mqtt = require('mqtt');
// 
//var io  = require('socket.io').listen(3000);
//var client = new mqtt.MQTTClient(1883, 'cd3307.myfoscam.org', 'pusher');
// 
//io.sockets.on('connection', function (socket) {
//	socket.on('subscribe', function (data) {
//    	console.log('Subscribing to '+data.topic);
//    	client.subscribe(data.topic);});
//});
// 
//client.addListener('mqttData', function(topic, payload)
//{
//	sys.puts(topic+'='+payload);
//  	io.sockets.emit('mqtt',{'topic':String(topic),
//  	'payload':String(payload)});
//});

var sys = require('sys');
var net = require('net');
var mqtt = require('mqtt');
 
var io  = require('socket.io').listen(3000);
var client = new mqtt.MQTTClient(1883, 'cd3307.myfoscam.org', 'pusher');
 
io.sockets.on('connection', function (socket) {
  socket.on('subscribe', function (data) {
    console.log('Subscribing to '+data.topic);
    client.subscribe(data.topic);
  });
});
 
client.addListener('mqttData', function(topic, payload){
  sys.puts(topic+'='+payload);
  io.sockets.emit('mqtt',{'topic':String(topic),
  'payload':String(payload)});
});