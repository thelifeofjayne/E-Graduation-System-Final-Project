import {URL, TYPE} from "../../config"

const _URL = URL.TODO.TODO_ALL
const _TYPE = TYPE.TODO.TODO_ALL

export function getTodo() {
    return dispatch => {
        fetch(_URL.GET_TODO, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_TODO,
                payload: json
            })
        })
    }
}
