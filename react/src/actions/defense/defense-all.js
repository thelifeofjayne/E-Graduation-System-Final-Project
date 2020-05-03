import {URL, TYPE} from "../../config"

const _URL = URL.DEFENSE.DEFENES_ALL
const _TYPE = TYPE.DEFENSE.DEFENSE_ALL

export function getDefense(calendar, level, semester, action) {
    return dispatch => {
        fetch(_URL.GET_DEFENSE(calendar, level, semester, action), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_DEFENSE,
                payload: json
            })
        })
    }
}

export function UpdateDefense(defenses) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_DEFENSE,
            payload: defenses
        })
    }
}

export function getAction() {
    return dispatch => {
        fetch(_URL.GET_ACTION, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ACTION,
                payload: json
            })
        })
    }
}

export function resetDefenseAll() {
    return dispatch => {
        dispatch({
            type: _TYPE.RESET
        })
    }
}



