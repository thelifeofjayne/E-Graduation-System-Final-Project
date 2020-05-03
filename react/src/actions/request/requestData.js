import {URL, TYPE} from "../../config"

const _URL = URL.REQUEST.REQUEST_DATA
const _TYPE = TYPE.REQUEST.REQUEST_DATA

export function deleteUserRequest(userRequest, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            student_id: userRequest.student.id,
            calendar_id: userRequest.calendar_item.calendar_id,
            action_id: userRequest.calendar_item.action_id,
            level_id: userRequest.calendar_item.level_id,
            semester_id: userRequest.calendar_item.semester_id,
            owner_id: userRequest.calendar_item.owner_id
        }))
        fetch(_URL.DELETE_USER_REQUEST, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            callback()
        })
    }
}

export function getAllUserRequest(calendar, level, semester, action) {
    return dispatch => {
        fetch(_URL.GET_ALL_USER_REQUEST(calendar, level, semester, action), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ALL_USER_REQUEST,
                payload: json
            })
        })
    }
}

export function UpdateUserRequestList(userRequests) {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_ALL_USER_REQUEST,
            payload: userRequests
        })
    }
}

export function getAllAction(callback = null) {
    return dispatch => {
        fetch(_URL.GET_ALL_ACTION, {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_ALL_ACTION,
                payload: json
            })
            if (callback !== null)
                callback(json)
        })
    }
}

export function getStep(actionId) {
    return dispatch => {
        fetch(_URL.GET_STEP(actionId), {
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(response => {
            dispatch({
                type: _TYPE.SET_STEP,
                payload: response
            })
        })
    }
}

export function updateResult(userRequest, defense, post, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            studentId: userRequest.student.id,
            calendarId: userRequest.calendar_item.calendar_id,
            actionId: userRequest.calendar_item.action_id,
            levelId: userRequest.calendar_item.level_id,
            semesterId: userRequest.calendar_item.semester_id,
            ownerId: userRequest.calendar_item.owner_id,
            defenseTypeId: defense.defense_type.action_id,
            subject: defense.subject,
            cond: post.cond,
            score: post.score,
            credit: post.credit,
            comment: post.comment,
            pass_check: post.pass_check
        }))
        fetch(_URL.UPDATE_RESULT, {
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

export function getDefenseStatus() {
    return dispatch => {
        fetch(_URL.GET_STATUS, {
            method: 'post',
            credentials: 'same-origin'
        }).then(response => {
            return response.json()
        }).then(json => {
            dispatch({
                type: _TYPE.SET_STATUS,
                payload: json
            })
        })
    }
}

export function togglePetition(checked, userRequest, requestDocument, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            studentId: userRequest.student.id,
            calendarId: userRequest.calendar_item.calendar_id,
            actionId: userRequest.calendar_item.action_id,
            levelId: userRequest.calendar_item.level_id,
            semesterId: userRequest.calendar_item.semester_id,
            documentId: requestDocument.document.document_id,
            ownerId: userRequest.calendar_item.owner_id,
            requestDocumentId: checked ? 1 : null
        }))
        fetch(_URL.UPDATE_REQUEST_DOCUMENT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            callback(response)
        })
    }
}

export function toggleFee(checked, userRequest, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            studentId: userRequest.student.id,
            calendarId: userRequest.calendar_item.calendar_id,
            actionId: userRequest.calendar_item.action_id,
            levelId: userRequest.calendar_item.level_id,
            semesterId: userRequest.calendar_item.semester_id,
            owner_id: userRequest.calendar_item.owner_id,
            paid: checked
        }))
        fetch(_URL.UPDATE_REQUEST_FEE, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            callback(response)
        })
    }
}

export function toggledefenseDocument(file, userRequest, defenseDocument, callback) {
    return dispatch => {
        const data = new FormData()
        data.append('json', JSON.stringify({
            studentId: userRequest.student.id,
            calendarId: userRequest.calendar_item.calendar_id,
            actionId: userRequest.calendar_item.action_id,
            levelId: userRequest.calendar_item.level_id,
            semesterId: userRequest.calendar_item.semester_id,
            documentId: defenseDocument.document.document_id,
            ownerId: userRequest.calendar_item.owner_id,
            defenseTypeId: userRequest.defenses[0].defense_type.action_id
        }))
        data.append('paper', file)
        fetch(_URL.UPDATE_DEFENSE_DOCUMENT, {
            method: 'post',
            body: data,
            credentials: 'same-origin'
        }).then(function (response) {
            return response.json()
        }).then(function (response) {
            callback(response)
        })
    }
}

export function updateUserRequest(index, userRequest) {
    return dispatch => {
        dispatch({
            type: _TYPE.UPDATE_USER_REQUEST,
            payload: {
                index, userRequest
            }
        })
    }
}

export function unmount() {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_UNMOUNT
        })
    }
}

export function resetRequestData() {
    return dispatch => {
        dispatch({
            type: _TYPE.SET_RESET
        })
    }
}



