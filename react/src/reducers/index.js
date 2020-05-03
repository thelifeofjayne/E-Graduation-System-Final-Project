import { combineReducers } from 'redux'

import main from './main'
import language from './language'
import calendar from './calendar/calendar'
import calendarInit from './calendar/calendar-init'
import calendarList from './calendar/calendarList'
import requestList from './request/requestList'
import requestAdd from './request/requestAdd'
import requestData from './request/requestData'
import evaluationList from './evaluation/evaluation-list'
import evaluationSubmit from './evaluation/evaluation-submit'
import evaluationAll from './evaluation/evaluation-all'
import defenseAll from './defense/defense-all'
import todoAll from './todo/todo-all'
import advisorLoad from './advisor/advisor-load'
import requestBypass from './request/request-bypass'

export default combineReducers({
    main,
    language,
    calendarList,
    calendar,
    calendarInit,
    requestList,
    requestAdd,
    requestData,
    evaluationList,
    evaluationSubmit,
    evaluationAll,
    defenseAll,
    todoAll,
    advisorLoad,
    requestBypass
})
