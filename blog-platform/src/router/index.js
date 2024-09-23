import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/auth";

// استيراد المكونات
import Home from "../components/Page/Post/Home.vue"
import AdminHome from "../components/Page/Admin/Home.vue"
import AdminPost from "../components/Page/Admin/Posts.vue"
import AdminRoles from "../components/Page/Admin/Roles.vue"
import PostInCategory from "../components/Page/Post/PostInCategory.vue"
import PostInUser from "../components/Page/Post/PostInUser.vue"
import PostPage from "../components/Page/Post/PostPage.vue"
import userProfile from "../components/Page/user/UserProfile.vue"
import editUserProfile from "../components/Page/user/EditUserProfile.vue"
import CreatePost from "../components/Page/Post/CreatePost.vue"
import EditPost from "../components/Page/Post/EditPost.vue"
import Login from "../components/Page/Auth/Login.vue"
import Register from "../components/Page/Auth/Register.vue"
import ForgotPsasword from "../components/Page/Auth/ForgotPsasword.vue"
import ResetPassword from "../components/Page/Auth/ResetPassword.vue"
import NotFound from "../components/Page/NotFound.vue";  // استيراد صفحة 404

const routes = [
  // المسارات المسجلة
  {
    path: "/",
    component: Home,
    name: "home"
  },
  {
    path: "/admin/user",
    component: AdminHome,
    name: "adminHome"
  },
  {
    path: "/admin/roles",
    component: AdminRoles,
    name: "roles"
  },
  {
    path: "/admin/posts",
    component: AdminPost,
    name: "adminPostUser"
  },
  {
    path: "/profile",
    component: userProfile,
    name: "profile"
  },
  {
    path: "/profile/edit",
    component: editUserProfile,
    name: "editProfile"
  },
  {
    path: "/post/create",
    component: CreatePost,
    name: "createPost"
  },
  {
    path: "/categories/:categoryName",
    component: PostInCategory,
    name: "postInCategory",
    props: route => ({ categoryName: route.params.categoryName, categoryId: route.params.categoryId }),
    beforeEnter: (to, from, next) => {
      to.params.categoryId = to.query.categoryId;
      next();
    }
  },
  {
    path: "/post/:postTitle",
    component: PostPage,
    name: "postPage",
    props: route => ({ postTitle: route.params.postTitle, postId: route.params.postId }),
    beforeEnter: (to, from, next) => {
      to.params.postId = to.query.postId;
      next();
    }
  },
  {
    path: "/post/:postTitle/edit",
    component: EditPost,
    name: "EditPost",
    props: route => ({ postTitle: route.params.postTitle, postId: route.params.postId }),
    beforeEnter: (to, from, next) => {
      to.params.postId = to.query.postId;
      next();
    }
  },
  {
    path: "/user/:userName",
    component: PostInUser,
    name: "postInUser",
    props: route => ({ userName: route.params.userName, userId: route.params.userId }),
    beforeEnter: (to, from, next) => {
      to.params.userId = to.query.userId;
      next();
    }
  },
  {
    path: "/login",
    component: Login,
    name: "login"
  },
  {
    path: "/register",
    component: Register,
    name: "register"
  },
  {
    path: "/forgot-psasword",
    component: ForgotPsasword,
    name: "ForgotPsasword"
  },
  {
    path: "/password-reset/:token",
    component: ResetPassword,
    name: "ResetPassword"
  },

  // المسار لمطابقة أي مسار غير مسجل - صفحة 404
  {
    path: "/:catchAll(.*)",  // يلتقط أي مسار غير مسجل
    component: NotFound,
    name: "NotFound",
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  // التحقق من وجود بيانات المستخدم
  if (!authStore.user) {
    try {
      await authStore.getUser();
    } catch (error) {
      return next({ name: 'login' });
    }
  }

  const isAuthenticated = authStore.user;

  // التحقق من الصلاحيات
  if (
    isAuthenticated &&
    isAuthenticated.roles[0].name !== 'admin' &&
    (to.name === 'adminHome' || to.name === 'login' || to.name === 'register' || to.name === 'ForgotPsasword' || to.name === 'ResetPassword')
  ) {
    next({ name: 'home' });
  } else if (
    isAuthenticated &&
    isAuthenticated.roles[0].name === 'admin' &&
    (to.name === 'login' || to.name === 'register' || to.name === 'ForgotPsasword' || to.name === 'ResetPassword')
  ) {
    next({ name: 'adminHome' });
  } else if (!isAuthenticated && to.name !== 'login' && to.name !== 'register' && to.name !== 'ForgotPsasword' && to.name !== 'ResetPassword') {
    next({ name: 'login' });
  } else {
    next();
  }
});

export default router;
