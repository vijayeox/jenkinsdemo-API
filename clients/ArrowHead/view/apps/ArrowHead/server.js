module.exports = (core, proc) => ({
  // When server initializes
  init: async () => {
  },

  // When server starts
  start: () => {},

  // When server goes down
  destroy: () => {},

  // When using an internally bound websocket, messages comes here
  onmessage: (ws, respond, args) => {
    respond('Pong');
  }
});
