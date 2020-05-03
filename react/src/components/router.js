import React from 'react'
import { HashRouter, Route, BrowserRouter } from 'react-router-dom'
import CalendarList from './calendar/calendar-list'
import Calendar from './calendar/calendar'
import Test from './test'
import { URL } from '../config'
import RequestList from './request/request-list'
import RequestAdd from './request/request-add'
import RequestAll from './request/request-all'
import CalendarInit from './calendar/calendar-init'
import EvaluationList from './evaluation/evaluation-list'
import EvaluationAdd from './evaluation/evaluation-add'
import EvaluationSubmit from './evaluation/evaluation-submit'
import EvaluationAll from './evaluation/evaluation-all'
import DefenseAll from './defense/defense-all'
import TodoAll from './todo/todo-all'
import AdvisorLoad from './advisor/advisor-load'
import RequestBypass from './request/request-bypass'

export default class Router extends React.Component {
    render() {
        const current = window.location.pathname
        const request = URL.EGS_BASE + URL.REQUEST.REQUEST_DATA.MAIN.PATH
        /*

        TODO: REMOVE REACT ROUTER AND CHANGE TO CONDITIONALLY SET PARAMS AS A GLOBAL VARIABLE

        */
        return (
            <HashRouter history={null}>
                <div>
                    <Route
                        path={URL.CALENDAR.CALENDAR_LIST.MAIN.PATH}
                        exact
                        render={() => <CalendarList />}
                    />
                    <Route
                        path={URL.CALENDAR.CALENDAR_INIT.MAIN.PATH}
                        exact
                        render={() => <CalendarInit />}
                    />
                    <Route
                        path={URL.CALENDAR.CALENDAR.MAIN.PATH}
                        exact
                        render={({ match }) => (
                            <Calendar calendarId={match.params.calendarId} />
                        )}
                    />

                    <Route
                        path={URL.TODO.TODO_ALL.MAIN.PATH}
                        exact
                        render={({ match }) => <TodoAll />}
                    />

                    <Route
                        path={URL.ADVISOR.ADVISOR_LOAD.MAIN.PATH}
                        exact
                        render={({ match }) => <AdvisorLoad />}
                    />

                    <Route
                        path={URL.REQUEST.REQUEST_BYPASS.MAIN.PATH}
                        exact
                        render={() => <RequestBypass />}
                    />

                    <Route
                        path={URL.REQUEST.REQUEST_LIST.MAIN.PATH}
                        exact
                        render={() => <RequestList />}
                    />
                    <Route
                        path={URL.REQUEST.REQUEST_ADD.MAIN.PATH}
                        exact
                        render={({ match }) => (
                            <RequestAdd
                                calendarId={match.params.calendarId}
                                levelId={match.params.levelId}
                                semesterId={match.params.semesterId}
                                actionId={match.params.actionId}
                                ownerId={match.params.ownerId}
                            />
                        )}
                    />
                    <Route
                        path={URL.REQUEST.REQUEST_DATA.MAIN.PATH}
                        exact
                        render={({ match }) => <RequestAll />}
                    />

                    <Route
                        path={URL.DEFENSE.DEFENES_ALL.MAIN.PATH}
                        exact
                        render={({ match }) => <DefenseAll />}
                    />

                    <Route
                        path={URL.EVALUATION.EVALUATION_LIST.MAIN.PATH}
                        exact
                        render={() => <EvaluationList />}
                    />
                    <Route
                        path={URL.EVALUATION.EVALUATION_ADD.MAIN.PATH}
                        exact
                        render={() => <EvaluationAdd />}
                    />
                    <Route
                        path={URL.EVALUATION.EVALUATION_SUBMIT.MAIN.PATH}
                        exact
                        render={() => <EvaluationSubmit />}
                    />
                    <Route
                        path={URL.EVALUATION.EVALUATION_ALL.MAIN.PATH}
                        exact
                        render={() => <EvaluationAll />}
                    />

                    <Route
                        path='/'
                        exact
                        render={() => <div>Nothing XD</div>}
                    />
                    <Route path='/test' exact render={() => <Test />} />
                </div>
            </HashRouter>
        )
    }
}
