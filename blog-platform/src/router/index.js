import { createRouter, createWebHistory } from "vue-router";
import Home from "../components/Page/Home.vue"
import Login from "../components/Page/Auth/Login.vue"
import Register from "../components/Page/Auth/Register.vue"
import ForgotPsasword from "../components/Page/Auth/ForgotPsasword.vue"
import ResetPassword from "../components/Page/Auth/ResetPassword.vue"
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
        {
            path:"/forgot-psasword",
            component:ForgotPsasword,
            name:"ForgotPsasword"
        },
        {
            path:"/password-reset/:token",
            component:ResetPassword,
            name:"ResetPassword"
        },
        //
    ]

    const reouter = createRouter({
        history:createWebHistory(),
        routes
    })
export default reouter;
//
