/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

import { createApp } from "vue";
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import "./assets/scss/soft-ui-dashboard.scss";
import PlayerIndex from "./components/PlayerIndex.vue";

const appInstance = createApp(
    PlayerIndex
);
appInstance.mount("#players");
