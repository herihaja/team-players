<script>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const players = ref([]);
export default {
    setup() {
        //const players = [];
        
        //const {players, getPlayers} = usePlayers();
        //onMounted(getPlayers);
        // getPlayers();
        //console.log(players);
        
        return {
            players
        }
    },
    mounted: () => {
        try {
            axios.get('/player/axios').then(response => {
                players.value.push(...response.data.data);
            });
            
        } catch (error) {
            console.error(error);
        }
    }
}
</script>
<template>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Player list</h6></div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0 mx-4">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Surname</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Team</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                            
                                <tr v-for="player in players" :key="player.id">
                                    <td>{{ player.name }}</td>
                                    <td>{{ player.surname }}</td>
                                    <td>{{ player.team }}</td>
                                    <td>
                                        <a :href="player.editLink">Edit</a> | 
                                        <a :href="player.transfertLink">Transfert</a>
                                    </td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>