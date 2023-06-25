<script>
import { ref } from 'vue';
import axios from 'axios';
import Pagination from "./Pagination.vue";

const players = ref([]);
const lastPage = ref(null);
const currentPage = ref(1);
const total = ref(null);
export default {
    components: {Pagination},
    setup() {
        return {
            players,
            lastPage,
            currentPage,
            total
        }
    },
    methods: {
        handleChildClick(data) {
            console.log(data);
            this.refreshData(data.link);
        },
        refreshData: (url) => {
            try {
                axios.get(url).then(response => {
                    players.value = response.data.data;
                    lastPage.value = response.data.lastPage;
                    currentPage.value = response.data.currentPage;
                    total.value = response.data.count;
                });
                
            } catch (error) {
                console.error(error);
            }
        }
    },
    mounted() {
        this.refreshData('/player/json');
    }
}
</script>
<template>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Player list (using VueJs)</h6></div>

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
                    <pagination :lastPage="lastPage" :currentPage="currentPage" :total="total" @clickedPageLink="handleChildClick">
                    </pagination>
                </div>
            </div>
            
        </div>
    </div>
    
</template>