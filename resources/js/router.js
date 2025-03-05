import { createRouter, createWebHistory } from "vue-router";
import Login from "../js/src/components/Login.vue";
import Register from "../js/src/components/Register.vue";
import Dashboard from "../js/src/components/Dashboard.vue";
import ViewPost from "../js/src/components/ViewPost.vue";
import AddPost from "../js/src/components/AddPost.vue";
import UpdatePost from "../js/src/components/UpdatePost.vue";

const routes = [
  { path: "/login", name: "Login", component: Login },
  { path: "/register", name: "Register", component: Register },
  {
    path: "/dashboard",
    name: "Dashboard",
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  { path: "/", redirect: "/login" },
  { path: "/viewpost", name: "viewPost", component: ViewPost, },
  { path: "/addpost", name: "addPost", component: AddPost, },
  { 
    path: "/updatepost/:id", 
    name: "updatePost",
    component: UpdatePost ,
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
