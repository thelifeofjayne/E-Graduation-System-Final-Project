import { TYPE } from '../../config'

const _TYPE = TYPE.REQUEST.REQUEST_BYPASS

const init = {
    action_bypasses: null,
    students: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_REQUEST_BYPASS:
            return {
                ...state,
                action_bypasses: action.payload
            }
        case _TYPE.SET_STUDENT:
            return {
                ...state,
                students: action.payload
            }
    }
    return state
}
