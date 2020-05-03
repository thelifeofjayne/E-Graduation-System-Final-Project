import {URL, TYPE} from "../../config"

const _URL = URL.ADVISOR.ADVISOR_LOAD
const _TYPE = TYPE.ADVISOR.ADVISOR_LOAD

export function getAdvisor() {
    return dispatch => {
        fetch(_URL.GET_ADVISOR, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ADVISOR,
                payload: json
            })
        })
    }
}
