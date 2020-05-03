import { TYPE } from '../../config'

const _TYPE = TYPE.REQUEST.REQUEST_LIST

const init = {
    calendarItems: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_CALENDAR_ITEM_WITH_STATUS:
            return {
                ...state,
                calendarItems: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
