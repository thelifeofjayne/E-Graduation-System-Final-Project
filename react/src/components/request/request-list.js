import React from 'react'
import { connect } from 'react-redux'
import {
    getCalendarItemWithStatus,
    resetRequestList
} from '../../actions/request/requestList'
import { getAllSemester } from '../../actions/calendar/calendar'
import { setHeader } from '../../actions/main'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import RequestListEach from './request-list-each'
import Loading from '../loading'

@connect(store => {
    return {
        lang: store.language.data,
        calendarItems: store.requestList.calendarItems,
        semesters: store.calendar.semesters,
        config: store.main.config
    }
})
export default class RequestList extends React.Component {
    componentWillUnmount() {
        const { dispatch } = this.props
        dispatch(resetRequestList())
    }

    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.request_list.head))
        dispatch(getCalendarItemWithStatus())
        dispatch(getAllSemester())
    }

    render() {
        const {
            calendarItems,
            semesters,
            requestList,
            lang,
            config
        } = this.props
        const calendarItemRedo =
            calendarItems === null
                ? null
                : calendarItems.filter(
                      calendarItem => calendarItem.owner_id !== config.SYSTEM_ID
                  )
        return calendarItemRedo === null ||
            calendarItems === null ||
            semesters === null ? (
            <Loading />
        ) : (
            <div>
                {calendarItemRedo.length === 0 ? null : (
                    <Row type='flex' justify='center'>
                        <Col sm={22} span={24}>
                            <h3>{lang.request_list.special}</h3>
                        </Col>
                        <Col sm={22} span={24}>
                            <table class='table'>
                                <thead>
                                    <tr>
                                        <th>
                                            <Col
                                                class='text-center table-col'
                                                sm={8}
                                                span={24}
                                            >
                                                {lang.request_list.list}
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={8}
                                                span={24}
                                            >
                                                {lang.request_list.range}
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={8}
                                                span={24}
                                            >
                                                {lang.request_list.status}
                                            </Col>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {calendarItemRedo.map(
                                        (calendarItem, index) => (
                                            <RequestListEach
                                                key={index}
                                                calendarItem={calendarItem}
                                            />
                                        )
                                    )}
                                </tbody>
                            </table>
                        </Col>
                    </Row>
                )}
                {semesters.map((semester, index) => {
                    const _calendarItem = calendarItems.filter(
                        calendarItem =>
                            calendarItem.semester_id === semester.semester_id &&
                            calendarItem.owner_id === config.SYSTEM_ID
                    )
                    return (
                        <Row key={index} type='flex' justify='center'>
                            <Col sm={22} span={24}>
                                <Row type='flex' justify='center'>
                                    <Col span={24}>
                                        <h3>{semester.semester_name}</h3>
                                    </Col>
                                    <Col span={24}>
                                        <table class='table'>
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <Col
                                                            class='text-center table-col'
                                                            sm={8}
                                                            span={24}
                                                        >
                                                            {
                                                                lang
                                                                    .request_list
                                                                    .list
                                                            }
                                                        </Col>
                                                        <Col
                                                            class='text-center table-col'
                                                            sm={8}
                                                            span={24}
                                                        >
                                                            {
                                                                lang
                                                                    .request_list
                                                                    .range
                                                            }
                                                        </Col>
                                                        <Col
                                                            class='text-center table-col'
                                                            sm={8}
                                                            span={24}
                                                        >
                                                            {
                                                                lang
                                                                    .request_list
                                                                    .status
                                                            }
                                                        </Col>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {_calendarItem.length === 0 ? (
                                                    <tr>
                                                        <td>{lang.nodata}</td>
                                                    </tr>
                                                ) : (
                                                    _calendarItem.map(
                                                        (
                                                            calendarItem,
                                                            index
                                                        ) => (
                                                            <RequestListEach
                                                                key={index}
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
                            </Col>
                        </Row>
                    )
                })}
            </div>
        )
    }
}
