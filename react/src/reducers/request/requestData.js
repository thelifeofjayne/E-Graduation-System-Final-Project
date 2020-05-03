import { TYPE } from '../../config'

const _TYPE = TYPE.REQUEST.REQUEST_DATA

const init = {
    steps: null,
    status: null,
    userRequests: null,
    actions: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.UPDATE_USER_REQUEST:
            return {
                ...state,
                userRequests: [
                    ...state.userRequests.slice(0, action.payload.index),
                    action.payload.userRequest,
                    ...state.userRequests.slice(action.payload.index + 1)
                ]
            }
        case _TYPE.SET_ALL_USER_REQUEST:
            return {
                ...state,
                userRequests: action.payload
            }
        case _TYPE.SET_ALL_ACTION:
            return {
                ...state,
                actions: action.payload
            }
        case _TYPE.SET_STEP:
            return {
                ...state,
                steps: action.payload
            }
        case _TYPE.SET_STATUS:
            return {
                ...state,
                status: action.payload
            }
        case _TYPE.SET_UNMOUNT:
            return {
                ...state,
                steps: null
            }
        case _TYPE.SET_RESET:
            return {
                ...state,
                status: null,
                userRequests: null
            }
    }
    return state
}
