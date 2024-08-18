import { defineStore } from "pinia";
import axios from "axios";

export const usePostStore = defineStore("post", {
  state: () => ({
    allPost: [],
    dataPost: {},
    allPostInUser: [],
    allPostInCategory: [],
    postErrors: [],
    postStatus: null,
    allTag: [],
    allCategory: [],

  }),
  getters: {
    posts: (satae) => satae.allPost,
    post: (satae) => satae.dataPost,
    postsInCategory: (satae) => satae.allPostInCategory,
    postsInUser: (satae) => satae.allPostInUser,
    tags: (satae) => satae.allTag,
    categories: (satae) => satae.allCategory,
    errors : (satae) => satae.postErrors,

  },
  actions: {
    async getAllPost() {
      try {
        const response = await axios.get("/api/v1/post");
        this.allPost = response.data.posts;
      } catch (error) {
        console.log(error);
      }
    },

    async getAllPostInCategory(category) {
      try {
        const response = await axios.get(`api/v1/categories/${category}/posts`);
        this.allPostInCategory = response.data.posts;
      } catch (error) {
        // console.log(error);
      }
    },
    async getAllPostInUser(user) {
      try {
        const response = await axios.get(`api/v1/user/${user}/post`);
        this.allPostInUser = response.data.allPostInUser;
      } catch (error) {
        // console.log(error);
      }
    },
    async getPost(postId) {
      try {
        const response = await axios.get(`api/v1/post/${postId}`);
        this.dataPost = response.data.post;
      } catch (error) {}
    },
    async getUserPosts(userId) {
      await axios.get(`api/v1/user/${userId}/post`);
      console.log();
    },
    async getAllTag() {
      try {
        const response = await axios.get("/api/v1/tag");
        this.allTag = response.data.tags;
      } catch (error) {
        console.log(error);
      }
    },
    async getAllCategory() {
      try {
        const response = await axios.get("/api/v1/category");
        console.log(response.data.category);
        this.allCategory = response.data.category;
      } catch (error) {
        console.log(error);
      }
    },
    stripHtml(html) {
        const tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        console.log(tmp.textContent);
        console.log(tmp.innerText);


        return tmp.textContent || tmp.innerText || "";
    },

    stripHtml(html) {
        const tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        const text = tmp.innerText.trim();

        if (text.length < 100 || text.length > 3000 || !text) {
            return [true, text];
        }
        return [false, html];
    },

    async  createPost(data) {
        const [isValid, contentText] = this.stripHtml(data.content);
        console.log(isValid);
        console.log(contentText);
        if(isValid){
            data.content = contentText
        }
        try {
            const response = await axios.post("/api/v1/post", data);
            console.log(response.data.post);
            this.router.push({
                name: "postPage",
                params: {
                    postTitle: response.data.post.title,
                },
                query: {
                    postId: `${response.data.post.id}`,
                },
            });
        } catch (error) {
            if(error.response.status === 422){
                this.postErrors = error.response.data.errors;
            }
        }
    },
    async updatePost (data,postId){
        const [isValid, contentText] = this.stripHtml(data.content);
        console.log(isValid);
        console.log(contentText);
        if(isValid){
            data.content = contentText
        }
        try {
            const response = await axios.put(`/api/v1/post/${postId}`, data);
            console.log(response.data.post);
            this.router.push({
                name: "postPage",
                params: {
                    postTitle: response.data.post.title,
                },
                query: {
                    postId: `${response.data.post.id}`,
                },
            });
        } catch (error) {
            if(error.response.status === 422){
                this.postErrors = error.response.data.errors;
            }
        }
    },
    async deletePost(postId){
        console.log(postId);

        try{

            await axios.delete(`/api/v1/post/${postId}`, );
            this.router.push("/")
        }
        catch(error){
            console.log(error);

        }
    }

  },
});
