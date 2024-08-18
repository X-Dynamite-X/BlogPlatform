import { defineStore } from "pinia";
import axios from "axios"

export const useAuthStore = defineStore('auth', {
    state: () => ({
        authUser:null,
        profileUser:null,
        authErrors:[],
        authStatus:null,

    }),
    getters : {
        user : (satae) => satae.authUser,
        profile :(satae) => satae.profileUser,
        erroes : (satae) => satae.authErrors,
        status : (satae) => satae.authStatus,


    },
    actions:{
        async getToken() {
            await axios.get('/sanctum/csrf-cookie')
        },
        async getUser() {
            await this.getToken();
            try {
                const data = await axios.get("/api/user");
                this.authUser = data.data;
                console.log("test",data);
            } catch (error) {
                this.authUser = null;
            }
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
            this.router.push("/login");
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
        async getProfile(userId){
            try{
                const response = await axios.get(`/api/v1/user/${userId}`);
                this.profileUser = response.data.profile;
                console.log(this.profileUser);
                console.log(this.profileUser.roles);
            }
            catch(error){
                console.log(error);
            }
        },

        async updateProfile(userId, data) {
            try {
                // Make a PUT request to update the user profile
                const response = await axios.put(`/api/v1/user/${userId}`, data);

                // Assuming you are using Vue Router, this is how you should navigate to the profile page
                this.router.push('/profile');  // Ensure `$router` is correctly accessible here

                // Update the profileUser with the returned data
                this.profileUser = response.data.profile;
            } catch (error) {
                // Handle the error appropriately (e.g., showing a notification or logging)
                console.error("Error updating profile:", error);
                // You could show an error message to the user or handle the error based on the status code
            }
        }

    }
});
