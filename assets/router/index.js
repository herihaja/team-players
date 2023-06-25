import { createRouter, createWebHistory } from "vue-router";
import Dashboard from "../views/Dashboard.vue";
import Tables from "../views/Tables.vue";
import Billing from "../views/Billing.vue";
import VirtualReality from "../views/VirtualReality.vue";
import Profile from "../views/Profile.vue";
import Rtl from "../views/Rtl.vue";
import SignIn from "../views/SignIn.vue";
import SignUp from "../views/SignUp.vue";

const routes = [
  {
    path: "/teamvue",
    name: "team.index",
    component: Tables,
  },
  {
    path: "/team/dashboard",
    name: "team.index",
    component: Tables,
  },
  {
    path: '/players/:id/show',
    name: 'players.show',
    component: Tables,
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL || ''),
  routes,
  linkActiveClass: "active",
});

export default router;
