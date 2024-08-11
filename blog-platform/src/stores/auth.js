import { defineStore } from "pinia";
import axios from "axios"

export const useAuthStore = defineStore('auth', {
    state: () => ({
        authUser:null,
        authErrors:[],
        authStatus:null,

    }),
    getters : {
        user : (satae) => satae.authUser,
        erroes : (satae) => satae.authErrors,
        status : (satae) => satae.authStatus,


    },
    actions:{
        async getToken() {
            await axios.get('/sanctum/csrf-cookie')
        },
        async getUser() {
            await this.getToken()
            const data = await axios.get("/api/user")
            this.authUser = data.data
        },
        async handleLogin(data){
            // this.authErrors=[];
            console.log(data);
            await this.getToken()
            try{
                await axios.post('/login', {
                    email: data.email,
                    password: data.password,
                    });
                this.router.push("/");
            }
            catch(error){
                if(error.response.status === 422){
                    this.authErrors = error.response.data.errors;
                }
            }

        },
        async handleRegister  (data)  {
            this.authErrors=[];
            await this.getToken()
            try{
                await axios.post('/register', {
                    name: data.name,
                    email: data.email,
                    password: data.password,
                    password_confirmation: data.password_confirmation,
                });
                await axios.post('/logout');
                this.router.push("/login");
            }
            catch(error){
                if(error.response.status === 422){
                    this.authErrors = error.response.data.errors;
                    console.log( this.authErrors);
                }
            }
        },
        async handleLogout(){
            await axios.post('/logout');
            this.authUser = null;
        },
        async handelForgotPsasword(data){
            this.authErrors=[];
            await this.getToken()
            try{
                const response = await axios.post('/forgot-password',{
                    email: data,
                });
                this.authStatus = response.data.status;
            }
            catch(error){
                if(error.response.status === 422){
                    this.authErrors = error.response.data.errors;
                }
            }


        },
        async handleResetPassword(resetData){
            this.authErrors=[];
            await this.getToken()
            try{
                await axios.post('/reset-password',resetData);

                this.router.push("/login");
                }
            catch(error){
                if(error.response.status === 422){
                    this.authErrors = error.response.data.errors;
                    }
                }
        },
    }
});
