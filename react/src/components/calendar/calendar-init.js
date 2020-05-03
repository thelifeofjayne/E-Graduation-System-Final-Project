import React from 'react'
import { connect } from 'react-redux'
import { setHeader } from '../../actions/main'
import DateRangePicker from 'react-bootstrap-daterangepicker'
import {
    getAllLevel,
    getAllSemester,
    updateCalendarItem
} from '../../actions/calendar/calendar'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import RequestListEach from '../request/request-list-each'
import {
    getCalendarItemInit,
    resetCalendarInit
} from '../../actions/calendar/calendar-init'

@connect(store => {
    return {
        lang: store.language.data,
        levels: store.calendar.levels,
        semesters: store.calendar.semesters,
        calendarItems: store.calendarInit.calendarItems
    }
})
export default class CalendarInit extends React.Component {
    componentWillUnmount() {
        const { dispatch } = this.props
        dispatch(resetCalendarInit())
    }

    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.calendar_init.head))
        dispatch(getCalendarItemInit())
        dispatch(getAllSemester())
        dispatch(getAllLevel())
    }

    render() {
        const { lang, calendarItems, levels, semesters } = this.props
        return levels === null || semesters === null || calendarItems === null
            ? null
            : levels.map((level, index) => (
                  <Row key={index}>
                      <Col class='text-center'>
                          <h3>{level.level_name}</h3>
                      </Col>
                      <Col>
                          {semesters.map((semester, index2) => {
                              const calendarItems_ = calendarItems.filter(
                                  calendarItem =>
                                      calendarItem.level_id ===
                                          level.level_id &&
                                      calendarItem.semester_id ===
                                          semester.semester_id
                              )
                              return (
                                  <Row
                                      key={`${index}${index2}`}
                                      type='flex'
                                      justify='center'
                                  >
                                      <Col sm={22} span={24}>
                                          <h4>{semester.semester_name}</h4>
                                      </Col>
                                      <Col sm={22} span={24}>
                                          <table class='table table-bordered'>
                                              <tbody>
                                                  {calendarItems_.length ===
                                                  0 ? (
                                                      <tr>
                                                          <td>{lang.nodata}</td>
                                                      </tr>
                                                  ) : (
                                                      calendarItems_.map(
                                                          (
                                                              calendarItem,
                                                              index3
                                                          ) => (
                                                              <RequestListEach
                                                                  key={`${index}${index2}${index3}`}
                                                                  calendarItem={
                                                                      calendarItem
                                                                  }
                                                              />
                                                          )
                                                      )
                                                  )}
                                              </tbody>
                                          </table>
                                      </Col>
                                  </Row>
                              )
                          })}
                      </Col>
                  </Row>
              ))
    }
}
