/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import { createApp } from "vue";
import App from "./App.vue";
import store from "./store";
import router from "./router";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import SoftUIDashboard from "./soft-ui-dashboard";
import PlayerIndex from "./components/PlayerIndex.vue";


const appInstance = createApp(
    PlayerIndex
    /*{
        components: PlayerIndex,
    }*/
);
//appInstance.use(store);
appInstance.use(router);
//appInstance.use(SoftUIDashboard);
appInstance.mount("#players");
