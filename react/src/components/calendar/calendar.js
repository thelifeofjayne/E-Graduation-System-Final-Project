import React from 'react'
import {connect} from 'react-redux'
import {setHeader} from '../../actions/main'
import {
    activateCalendar, deleteCalendar,
    getActiveActionItem,
    getAllCalendarItem,
    getAllLevel,
    getAllSemester,
    getCalendar,
    resetCalendarLevel,
    updateCalendarItem
} from '../../actions/calendar/calendar'
import DateRangePicker from 'react-bootstrap-daterangepicker'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'

@connect((store) => {
    return {
        calendarItems: store.calendar.calendarItems,
        semesters: store.calendar.semesters,
        actionItems: store.calendar.actionItems,
        levels: store.calendar.levels,
        calendar: store.calendar.calendar,
        lang: store.language.data
    }
})
export default class Calendar extends React.Component {

    constructor(props) {
        super(props)
        const {lang} = props
        this.locale_en = {}
        this.locale_th = {
            "applyLabel": 'ยืนยัน',
            "cancelLabel": 'ยกเลิก',
            "daysOfWeek": ["อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"],
            "monthNames": [
                "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
                "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
            ],
            "firstDay": 1
        }
    }

    componentWillUnmount() {
        const {dispatch} = this.props
        dispatch(resetCalendarLevel())
    }

    componentDidMount() {
        const {dispatch, calendarId, lang} = this.props
        dispatch(setHeader(`${lang.calendar.head} ${calendarId}`))
        dispatch(getAllCalendarItem(calendarId))
        dispatch(getAllSemester())
        dispatch(getActiveActionItem())
        dispatch(getAllLevel())
        dispatch(getCalendar(calendarId))
    }

    dateRangeApply(ev, picker, calendarItem) {
        const {dispatch, calendarItems} = this.props
        const idx = calendarItems.findIndex(_calendarItem => _calendarItem === calendarItem)
        const _calendarItem = Object.assign({}, calendarItems[idx], {
            calendar_item_date_start: moment(new Date(picker.startDate._d)).format('YYYY-MM-DD'),
            calendar_item_date_end: moment(new Date(picker.endDate._d)).format('YYYY-MM-DD')
        })
        dispatch(updateCalendarItem(idx, _calendarItem))
    }

    calendarItemAllSet() {
        const {calendarItems} = this.props
        let allSet = true
        calendarItems.map(calendarItem => {
            if (calendarItem.calendar_item_date_start === null || calendarItem.calendar_item_date_start === null)
                allSet = false
        })
        return allSet
    }

    activateCalendar() {
        const {dispatch, calendar} = this.props
        dispatch(activateCalendar(calendar))
    }

    delete() {
        const {calendar, dispatch} = this.props
        dispatch(deleteCalendar(calendar.calendar_id))
    }

    render() {
        const {semesters, calendarItems, actionItems, levels, calendar, lang} = this.props
        return calendar === null || levels === null ? null :
            [
                levels.map(level =>
                    <div key={level.level_id}>
                        <div>
                            <h4>{`${lang.calendar.calendar} ${level.level_name}`}</h4>
                        </div>
                        <table class='table'>
                            <thead>
                            <tr>
                                <th>
                                    <Col class='text-center table-col'
                                         sm={6} span={24}>
                                        {lang.calendar.list}
                                    </Col>
                                    {
                                        semesters === null ? null : semesters.map(
                                            semester =>
                                                <Col key={semester.semester_id} class='text-center table-col'
                                                     sm={6} span={24}>
                                                    {semester.semester_name}
                                                </Col>
                                        )
                                    }
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            {
                                actionItems === null || calendarItems === null || semesters === null ? null :
                                    actionItems.filter(actionItem => actionItem.level_id === level.level_id).map(
                                        (actionItem, index) =>
                                            <tr key={index}>
                                                <td>
                                                    <Row class='table-row' type='flex'>
                                                        <Col class='text-center table-col' sm={6} span={24}>
                                                            {actionItem.action.action_name}
                                                        </Col>
                                                        {
                                                            semesters.map(
                                                                (semester, index2) => {
                                                                    let calendarItems_ = calendarItems.filter(_calendarItem =>
                                                                        _calendarItem.action_id === actionItem.action.action_id &&
                                                                        _calendarItem.semester_id === semester.semester_id &&
                                                                        _calendarItem.level_id === level.level_id)
                                                                    let calendarItem, start, end
                                                                    if (calendarItems_.length !== 0) {
                                                                        calendarItem = calendarItems_[0]
                                                                        start = moment(new Date(calendarItem.calendar_item_date_start))
                                                                        end = moment(new Date(calendarItem.calendar_item_date_end))
                                                                    }
                                                                    return (
                                                                        <Col key={`${index}${index2}`}
                                                                             class='text-center table-col' sm={6}
                                                                             span={24}>
                                                                            {
                                                                                calendarItems_.length === 0 ?
                                                                                    <div class='invisible'>.</div> :
                                                                                    <div>
                                                                                        {
                                                                                            calendarItem.calendar_item_date_start === null ? '-' :
                                                                                                `${start.format('LL')} - ${end.format('LL')}`

                                                                                        }
                                                                                        <DateRangePicker opens='left'
                                                                                                         autoUpdateInput={false}
                                                                                                         startDate={calendarItem.calendar_item_date_start === null ? moment(new Date()) : start}
                                                                                                         endDate={calendarItem.scalendar_item_date_end === null ? moment(new Date()) : end}
                                                                                                         onApply={(ev, picker) => this.dateRangeApply(ev, picker, calendarItems_[0])}
                                                                                                         locale={lang.lang === 'en' ? null : this.locale_th}>
                                                                                            <Tag
                                                                                                class='tag-default clickable'>
                                                                                                {lang.calendar.edit}
                                                                                            </Tag>
                                                                                        </DateRangePicker>
                                                                                    </div>

                                                                            }
                                                                        </Col>
                                                                    )
                                                                }
                                                            )
                                                        }
                                                    </Row>
                                                </td>
                                            </tr>
                                    )
                            }
                            </tbody>
                        </table>
                    </div>
                ),
                calendar.calendar_active ?
                    <Tag class='tag-success tag-big margin-0'>
                        {lang.calendar.activated}
                    </Tag> :
                    <button disabled={calendarItems === null ? true : !this.calendarItemAllSet()}
                            onClick={() => this.activateCalendar()}
                            class='btn btn-lg btn-block btn-success'>
                        {lang.calendar.activate}
                    </button>
            ]
    }
}