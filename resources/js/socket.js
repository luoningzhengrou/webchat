import io from 'socket.io-client';

const socket = io('http://webchats.test', {
    path: '/ws',
    transports: ['websocket']
});

export default socket;
