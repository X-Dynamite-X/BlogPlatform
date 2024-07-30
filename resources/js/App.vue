<template>
    <router-view></router-view>
</template>

<script>
    import LoginPages from "./Auth/LoginPages.vue";
    import SignInPages from "./Auth/SignInPages.vue";

export default {
    components:{
        LoginPages,
        SignInPages
    },

}
</script>


