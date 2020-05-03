import { TYPE } from '../../config'

const _TYPE = TYPE.CALENDAR.CALENDAR_LIST

const init = {
    all: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_ALL:
            return {
                ...state,
                all: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
