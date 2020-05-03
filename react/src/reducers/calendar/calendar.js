import { TYPE } from '../../config'

const _TYPE = TYPE.CALENDAR.CALENDAR

const init = {
    calendarItems: null,
    semesters: null,
    actionItems: null,
    levels: null,
    calendar: null
}

export default function reducer(state = init, action) {
    switch (action.type) {
        case _TYPE.SET_ALL_CALENDAR_ITEM:
            return {
                ...state,
                calendarItems: action.payload
            }
        case _TYPE.SET_ALL_LEVEL:
            return {
                ...state,
                levels: action.payload
            }
        case _TYPE.SET_ALL_SEMESTER:
            return {
                ...state,
                semesters: action.payload
            }
        case _TYPE.SET_ALL_ACTION_ITEM:
            return {
                ...state,
                actionItems: action.payload
            }
        case _TYPE.UPDATE_CALENDAR_ITEM:
            return {
                ...state,
                calendarItems: [
                    ...state.calendarItems.slice(0, action.payload.idx),
                    action.payload.calendarItem,
                    ...state.calendarItems.slice(action.payload.idx + 1)
                ]
            }
        case _TYPE.SET_CALENDAR:
            return {
                ...state,
                calendar: action.payload
            }
        case _TYPE.RESET:
            return init
    }
    return state
}
