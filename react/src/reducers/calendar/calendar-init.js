import { TYPE } from '../../config'

const _TYPE = TYPE.CALENDAR.CALENDAR_INIT

const init = {
    calendarItems: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_ALL_CALENDAR_ITEM:
            return {
                ...state,
                calendarItems: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
