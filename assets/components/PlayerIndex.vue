<script>
import { ref } from 'vue';
import axios from 'axios';
import Pagination from "./Pagination.vue";

const players = ref([]);
const lastPage = ref(null);
const currentPage = ref(1);
const total = ref(null);
const teams = ref([]);

export default {
    components: {Pagination},
    setup() {
        return {
            players,
            lastPage,
            currentPage,
            total,
            teams
        }
    },
    methods: {
        handleChildClick(data) {
            this.refreshData(data.link);
        },
        refreshData: (url) => {
            const loader = document.getElementById('loader');
            const teamFilter = document.getElementById('player_team').value;
            const search = document.getElementById('player_search').value;
            url = `${url}&team=${teamFilter}&search=${search}`;
            loader.style.display = 'block';
            players.value = [];
            axios.get(url).then(response => {
                players.value = response.data.data;
                lastPage.value = response.data.lastPage;
                currentPage.value = response.data.currentPage;
                total.value = response.data.count;
            })
            .catch(error => {
                console.error(error);
            })
            .finally(() => {
                loader.style.display = 'none';
            });
        },
        triggerFilter(){
            this.refreshData('/player/json?');
        }
    },
    mounted() {
        try {
            axios.get('/team/json').then(response => {
                teams.value = response.data.data;
            });
        } catch (error) {
            console.error(error);
        }
        this.refreshData('/player/json?');
    }
}
</script>
<template>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0"><h6>Player list (using VueJs)</h6></div>

                <div class="card-body px-0 pt-0 pb-2">

                    <div class="row px-4">
                        
                        <div class="form-group col-4">
                            <div class="">
                                Search:
                                <input name="search" id="player_search" class="form-control form-control-default"/>
                            </div>
                        </div>
                        <div class="form-group col-4">
                            <div class="">
                                Team:
                                <select id="player_team" name="team_filter" class="form-control form-control-default">
                                    <option value="">Select a team</option>
                                    <option v-for="option in teams" :key="option.id" :value="option.id">{{ option.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2"/>
                        <div class="form-group col-2">
                            <div class="">
                                <input type="button" value="Search" :onClick="triggerFilter" class="btn mb-0 bg-gradient-dark btn-md w-100 null my-4 mb-2"/>
                            </div>
                        </div>
                    </div>

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
                        <div id="loader" class="w-3 h-3 mx-30"><img class="w-100 h-100" src="../assets/img/loading.gif"/></div>
                    </div>
                    <pagination :lastPage="lastPage" :currentPage="currentPage" :total="total" @clickedPageLink="handleChildClick">
                    </pagination>
                </div>
            </div>
            
        </div>
    </div>
    
</template>