import React from 'react'
import { connect } from 'react-redux'
import {
    deleteCalendar,
    getAllCalendar,
    insertCalendar,
    resetCalendarList,
    setCalendar
} from '../../actions/calendar/calendarList'
import { setHeader } from '../../actions/main'
import { URL } from '../../config'
import Popover from 'antd/lib/popover'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Icon from 'antd/lib/icon'
import InputNumber from 'antd/lib/input-number'
import Tag from 'antd/lib/tag'
import Loading from '../loading'
import Popconfirm from 'antd/lib/popconfirm'

@connect(store => {
    return {
        container: store.main.container,
        calendars: store.calendarList.all,
        post: store.calendarList.post,
        active: store.calendarList.active,
        lang: store.language.data
    }
})
export default class CalendarList extends React.Component {
    constructor() {
        super()
        this.state = {
            year: null,
            visible: false,
            loading: false,
            deleting: false
        }
    }

    componentWillUnmount() {
        const { dispatch } = this.props
        dispatch(resetCalendarList())
    }

    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.calendar_list.head))
        dispatch(
            getAllCalendar(response => {
                this.setState({ year: response[0].calendar_id + '' })
            })
        )
    }

    changeYear(year) {
        console.log(year)
        year = year + ''
        this.setState({ year })
    }

    submitYear() {
        const { post, dispatch, active, container } = this.props
        const { year, loading } = this.state
        if (loading) return false
        if (year === null || year === '') alert('YEAR NULL', 'PLS TRY AGAIN')
        else if (year.length !== 4) alert('YEAR 4 CHAR', 'PLS TRY AGAIN')
        else if (isNaN(year)) alert('NUMBER ONLY', 'PLS TRY AGAIN')
        else {
            this.setState({ loading: true })
            dispatch(
                insertCalendar(year, resp => {
                    if (resp !== 1) alert('DUPLICATE', 'PLS TRY AGAIN')
                    else {
                        dispatch(getAllCalendar())
                        this.setState({ visible: false, year: null })
                    }
                    this.setState({ loading: false })
                })
            )
        }
    }

    visible(visible) {
        this.setState({ visible })
    }

    delete(calendar_id) {
        const { calendars, dispatch } = this.props
        const { deleting } = this.state
        if (deleting) return false
        this.setState({ deleting: true })
        dispatch(
            deleteCalendar(calendar_id, () => {
                const _calendars = calendars.filter(
                    calendar => calendar.calendar_id !== calendar_id
                )
                dispatch(setCalendar(_calendars))
                this.setState({ deleting: false })
            })
        )
    }

    render() {
        const { calendars, active, lang } = this.props
        const { year, visible, loading, deleting } = this.state
        return calendars === null ? (
            <Loading />
        ) : (
            <Row>
                <Col span={24} style={{ textAlign: 'right', marginBottom: 8 }}>
                    <Popover
                        placement='right'
                        trigger='click'
                        visible={visible}
                        content={
                            <Row>
                                <InputNumber
                                    class='text-center'
                                    value={
                                        year === null
                                            ? calendars[0].calendar_id
                                            : year
                                    }
                                    style={{ width: 100 }}
                                    placeholder={lang.calendar_list.placeholder}
                                    maxLength={4}
                                    onChange={value => this.changeYear(value)}
                                />
                                <Tag
                                    class='clickable tag-medium tag-success'
                                    onClick={() => this.submitYear()}
                                >
                                    {loading ? (
                                        <Icon type='loading' />
                                    ) : (
                                        lang.calendar_list.add
                                    )}
                                </Tag>
                            </Row>
                        }
                    >
                        <Tag
                            class='tag-default tag-medium clickable'
                            onClick={() => this.visible(!visible)}
                        >
                            <Icon
                                style={{ marginRight: 8 }}
                                type='plus-circle-o'
                            />
                            {lang.calendar_list.add_calendar}
                        </Tag>
                    </Popover>
                </Col>
                <Col span={24}>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.calendar_list.year}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={6}
                                            span={24}
                                        >
                                            {lang.calendar_list.active_status}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={12}
                                            span={24}
                                        >
                                            {lang.calendar_list.manage}
                                        </Col>
                                    </Row>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {calendars.length === 0 ? (
                                <tr>
                                    <td>
                                        <Row class='table-row' type='flex'>
                                            <Col
                                                class='text-center table-col'
                                                span={24}
                                            >
                                                {lang.nodata}
                                            </Col>
                                        </Row>
                                    </td>
                                </tr>
                            ) : (
                                calendars.map((calendar, index) => (
                                    <tr key={index}>
                                        <td>
                                            <Row class='table-row' type='flex'>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    {calendar.calendar_id}
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    {calendar.calendar_active ? (
                                                        <Tag class='tag-success'>
                                                            {
                                                                lang
                                                                    .calendar_list
                                                                    .currnet_active
                                                            }
                                                        </Tag>
                                                    ) : (
                                                        <Tag class='tag-default'>
                                                            {
                                                                lang
                                                                    .calendar_list
                                                                    .inactive
                                                            }
                                                        </Tag>
                                                    )}
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    <a
                                                        href={URL.CALENDAR.CALENDAR.MAIN.LINK(
                                                            calendar.calendar_id
                                                        )}
                                                    >
                                                        <Tag class='tag-default clickable'>
                                                            {
                                                                lang
                                                                    .calendar_list
                                                                    .manage_calendar
                                                            }
                                                        </Tag>
                                                    </a>
                                                </Col>
                                                <Col
                                                    class='text-center table-col'
                                                    sm={6}
                                                    span={24}
                                                >
                                                    {calendar.calendar_active ? (
                                                        <Tag class='tag-default'>
                                                            -
                                                        </Tag>
                                                    ) : (
                                                        <Popconfirm
                                                            title='ยืนยัน?'
                                                            onConfirm={() =>
                                                                this.delete(
                                                                    calendar.calendar_id
                                                                )
                                                            }
                                                            okText='ยืนยัน'
                                                            cancelText='ยกเลิก'
                                                        >
                                                            <Tag class='tag-error clickable'>
                                                                {deleting ? (
                                                                    <Icon type='loading' />
                                                                ) : (
                                                                    lang
                                                                        .calendar_list
                                                                        .delete
                                                                )}
                                                            </Tag>
                                                        </Popconfirm>
                                                    )}
                                                </Col>
                                            </Row>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </Col>
            </Row>
        )
    }
}
