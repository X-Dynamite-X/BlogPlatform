import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/Page/Home.vue"
import Login from "../components/Page/Auth/Login.vue"
import Register from "../components/Page/Auth/Register.vue"


const routes =[
    {
        path:"/",
        component:Home,
        name:"home"
    },
    {
        path:"/login",
        component:Login,
        name:"login"
    },
    {
        path:"/register",
        component:Register,
        name:"register"
    },
]

const reouter = createRouter({
    history:createWebHistory(),
    routes
})
export default reouter;
//
