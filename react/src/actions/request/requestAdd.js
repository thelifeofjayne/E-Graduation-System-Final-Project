import {URL, TYPE} from "../../config"
import 'whatwg-fetch'

const _URL = URL.REQUEST.REQUEST_ADD
const _TYPE = TYPE.REQUEST.REQUEST_ADD

export function setCurrent(current) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_CURRENT,
            payload: current
        })
    }
}

export function updateProject(name_th, name_en, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({name_th, name_en}))
        fetch(_URL.ADD_PROJECT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            callback(json)
        })
    }
}


export function getProject() {
    return dispatch => {
        fetch(_URL.GET_PROJECT, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_PROJECT,
                payload: json
            })
        })
    }
}

export function setProject(json) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_PROJECT,
            payload: json
        })
    }
}


export function setComponent(component) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_COMPONENT,
            payload: component
        })
    }
}

export function getCalendarItem(ownerId, calendarId, levelId, semesterId, actionId, callback) {
    return dispatch => {
        fetch(_URL.GET_CALENDAR_ITEM(ownerId, calendarId, levelId, semesterId, actionId), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_CALENDAR_ITEM,
                payload: json
            })
            callback(json)
        })
    }
}

export function getStep(actionId, callback) {
    return dispatch => {
        fetch(_URL.GET_STEP(actionId), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(response => {
            callback(response)
            dispatch({
                type: _TYPE.SET_STEP,
                payload: response
            })
        })
    }
}

export function getTeacher(load) {
    return dispatch => {
        fetch(_URL.GET_ALL_TEACHER(load), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ALL_TEACHER,
                payload: json
            })
        })
    }
}

export function getPosition(actionId) {
    return dispatch => {
        fetch(_URL.GET_ALL_POSITION(actionId), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ALL_POSITION,
                payload: json
            })
        })
    }
}

export function getRoom() {
    return dispatch => {
        fetch(_URL.GET_ALL_ROOM, {
            credentials: 'same-origin'
        }).then(resp => {
            return resp.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ALL_ROOM,
                payload: json
            })
        })
    }
}

export function setPost(post) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_POST,
            payload: post
        })
    }
}

export function insert(post, calendarItem, callback) {
    return dispatch => {
        const data = new FormData()
        post = {
            ...post,
            calendarItem: {
                calendarId: calendarItem.calendar.calendar_id,
                actionId: calendarItem.action.action_id,
                levelId: calendarItem.level.level_id,
                semesterId: calendarItem.semester.semester_id,
                owner_id: calendarItem.owner_id,
            }
        }
        data.append('json', JSON.stringify(post))
        fetch(_URL.INSERT_USER_REQUEST, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            callback(json)
        })
    }
}

export function getDefenseEvent(calendarItem, defense, teachers, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            teachers,
            calendarId: calendarItem.calendar.calendar_id,
            ownerId: calendarItem.owner_id,
            levelId: calendarItem.level.level_id,
            semesterId: calendarItem.semester.semester_id,
            defenseTypeId: defense.type
        }))
        fetch(_URL.GET_DEFENSE_EVENT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            callback(json)
        })
    }
}

export function resetRequestAdd() {
    return dispatch => {
        dispatch({
            type: _TYPE.RESET
        })
    }
}
