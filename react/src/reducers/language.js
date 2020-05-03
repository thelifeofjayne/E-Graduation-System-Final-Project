import { TYPE } from '../config'

const _TYPE = TYPE.LANGUAGE

const init = {
    data: null
}
export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_DATA:
            return {
                ...state,
                data: action.payload
            }
    }
    return state
}
