import {createStore, Store} from "vuex";
import {PostInterface, PostListInterface, postSavePayload} from "../types";
import axiosClient from "../axios";

let store: Store<object> = createStore({
    state: {
        user: {
            name: 'behzad kahvand',
            email: 'behzad.kahvand@gmail.com',
            imageUrl:
                'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
        },
        postList: {} as PostListInterface
    },
    mutations: {
        setPostList(state, postList: PostListInterface) {
            state.postList = postList
        }
    },
    actions: {
        savePost({commit}, payload: postSavePayload) {
            if (payload.isUpdate) {
                return axiosClient.put("/" + payload.post.id, payload.post)
            } else {
                return axiosClient.post("/", payload.post)
            }
        },
        getPost({commit}, id: string) {
            return axiosClient.get("/" + id)
        },
        getPosts({commit}, queryParam: string) {
            return axiosClient.get("/?query=" + queryParam)
                .then(({data}) => {
                    commit('setPostList', data.results)
                })
        },
        deletePost({commit}, id: string) {
            return axiosClient.delete("/" + id)
        }
    }
})

export default store;
