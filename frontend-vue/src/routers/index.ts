import {createRouter, createWebHistory} from "vue-router";
import defaultLayout from "../components/DefaultLayout.vue";
import listPost from "../views/ListPost.vue";
import savePost from "../views/SavePost.vue";

let routes: any = [
    {
        path: '/',
        redirect: '/list',
        name: "dashboard",
        component: defaultLayout,
        children: [
            {
                path: '/list',
                name: "listPosts",
                component: listPost
            },
            {
                path: '/save/:id?',
                name: "savePost",
                component: savePost
            }
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
