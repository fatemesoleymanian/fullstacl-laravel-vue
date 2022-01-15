import App from './components/AppComponent.vue'
import Tags from './admin/pages/TagsComponent'
import Category from "./admin/pages/CategoryComponent";
import AdminUsers from "./admin/pages/AdminUsers";
import login from "./admin/pages/login";
import role from "./admin/pages/Role";
import assignRole from "./admin/pages/AssignRole";
import createBlog from "./admin/pages/CreateBlog";
import editBlog from "./admin/pages/EditBlog";
import BlogComments from "./admin/pages/BlogComments";
import CommentReply from "./admin/pages/CommentReply";
import blogs from "./admin/pages/Blogs";
import notfound from "./admin/pages/notFound";
import usecomp from "./vuex/usecomp";

export const routes = [
    {
        path: '/home',
        component: App,
        name:'home'
    },
    {
        path: '/dashboard',
        component: App,
        name:'dashboard'
    },
    {
        path:'/tags',
        component: Tags,
        name:'tags'
    },
    {
        path:'/category',
        component: Category,
        name:'category'
    },
    {
        path:'/admin',
        component: AdminUsers,
        name:'admin'
    },
    {
        path:'/test',
        component: usecomp
    },
    {
        path:'/login',
        component: login,
        name:'login'
    },
    {
        path:'/role',
        component: role,
        name:'role'
    },
    {
        path:'/assignrole',
        component: assignRole,
        name:'assignRole'
    },
    {
        path:'/createblog',
        component: createBlog,
        name:'createblog'
    },
    {
        path:'/blogs',
        component: blogs,
        name:'blogs'
    },
    {
        path:'/editblog/:id',
        component: editBlog,
        name:'editblog'
    },
    {
        path: '*',
        component: notfound,
        name: 'notfound'

    },
    {
        path:'/get_comments/:id',
        component: BlogComments,
        name:'blogcomments',
        meta:{
            navLink: '/get_comments'
        }
    },
    {
        path:'/get_reply/:id',
        component: CommentReply,
        name:'commentreply'
    },

];
