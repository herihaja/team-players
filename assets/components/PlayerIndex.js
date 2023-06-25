import axios from 'axios';

export default function usePlayers() {
  const players = [];
  const player = [];

  const getPlayers = async () => {
    console.log("dans get players");
    try {
      const response = await axios.get('/player/axios');
      players.push(...response.data.data);
      console.error("apres axios appel");
    } catch (error) {
      console.error(error);
    }
  };

  const getPlayer = async (id) => {
    console.error("dans get player zao");
    try {
      const response = await axios.get(`/player/{id}/axios/`);
      player.length = 0; // Clear the array
      player.push(response.data.data);
    } catch (error) {
      console.error(error);
    }
  };

  return {
    players,
    player,
    getPlayer,
    getPlayers,
  };
}
