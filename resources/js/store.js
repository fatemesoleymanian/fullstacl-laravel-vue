import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

export default new Vuex.Store({
    state :{
        deleteModalObj :
            {
                deleteModal :false,
                dataUrl : '',
                data : null,
                deletingIndex:null,
                isDeleted : false
            },
        user:false,
    },
    getters:{
     getCounter(state)
     {
        if(state.counter > 1003) return 'this is too big!'
        return state.counter;
     },
     getDeleteModalObj(state)
     {
         return state.deleteModalObj;
     }
    },
    mutations:{
        ChangeTheCounter(state , data)
        {
            state.counter += data
        },
        setDeleteModal(state,data)
        {
            const deleteModalObj =
                {
                    deleteModal :false,
                    dataUrl : '',
                    data : null,
                    deletingIndex:state.deleteModalObj.deletingIndex,
                    isDeleted : data
                }
            state.deleteModalObj = deleteModalObj;
            console.log(deleteModalObj);

        },
        setDeleteModalObj(state ,data)
        {
            state.deleteModalObj = data;
        },
        updateUser(state, data)
        {
           state.user=data
        }

    }
})
