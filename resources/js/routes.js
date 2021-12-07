import App from './components/AppComponent.vue'
import Tags from './admin/pages/TagsComponent'
import Category from "./admin/pages/CategoryComponent";
import AdminUsers from "./admin/pages/AdminUsers";
import login from "./admin/pages/login";
import role from "./admin/pages/Role";
import usecomp from "./vuex/usecomp";

export const routes = [
    {
        path: '/',
        component: App
    },
    {
        path: '/home',
        component: App
    },
    {
        path:'/tags',
        component: Tags
    },
    {
        path:'/category',
        component: Category
    },
    {
        path:'/admin',
        component: AdminUsers
    },
    {
        path:'/test',
        component: usecomp
    },
    {
        path:'/login',
        component: login
    },
    {
        path:'/role',
        component: role
    },

];
