import { defineStore } from "pinia";

import axios from "axios"
export const useAdminStore = defineStore('admin', {
    state: () => ({
        allUsers:[],
        allRoles:[],
        allPosts:[],

    }),
    getters : {
        users : (satae) => satae.allUsers,
        roles : (satae) => satae.allRoles,
        posts : (sates) => sates.allPosts,
    },
    actions:{
        async getAllUsers() {
            try {
                const response = await axios.get("/api/v1/admin/user");
                this.allUsers = response.data.users;
                this.allRoles = response.data.roles;
            } catch (error) {
                console.error('Error fetching users and roles:', error);
            }
        },
        async updateUser(user) {
            console.log(user.role );

            try {
                const response = await axios.put(`/api/v1/admin/user/${user.id}`, user);

                const updatedUser = response.data.user;

                // تحديث المستخدم في قائمة المستخدمين
                const index = this.users.findIndex((u) => u.id === updatedUser.id);
                if (index !== -1) {
                    this.users[index] = updatedUser;
                }
            } catch (error) {
                console.error("Error updating user:", error.response.data.message);
            }
        },
        async deleteUser(userId) {
            try {
                await axios.delete(`/api/v1/admin/user/${userId}`);
                this.users = this.users.filter((user) => user.id !== userId);
            } catch (error) {
                console.error("Error deleting user:", error);
            }
        },
        async getAllPost() {
            try {
                const response = await axios.get("/api/v1/admin/post");
                console.log(response.data);

                this.allPosts = response.data.posts;

            } catch (error) {
                console.error('Error fetching users and roles:', error);
            }
        },
        async deletePost(postId) {

            try {
                await axios.delete(`/api/v1/admin/post/${postId}`);
                this.posts = this.posts.filter((post) => post.id !== postId);

            } catch (error) {
                console.error("Error deleting user:", error);
            }
        },
    }
});
