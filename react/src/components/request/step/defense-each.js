import React from 'react'
import { connect } from 'react-redux'
import Button from '../step/button'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../../loading'
import { getDefenseEvent, setPost } from '../../../actions/request/requestAdd'
import ReactDOMServer from 'react-dom/server'
import Test from '../../test'
import Select from 'antd/lib/select'

const Option = Select.Option

@connect(
    store => {
        return {
            lang: store.language.data,
            calendarItem: store.requestAdd.calendarItem,
            rooms: store.requestAdd.rooms,
            post: store.requestAdd.post
        }
    },
    null,
    null,
    { withRef: true }
)
export default class DefenseEach extends React.Component {
    constructor(props) {
        super(props)
        this.calendar
        this.defense = {
            type: null,
            room: null,
            date: null,
            start: null,
            end: null
        }
        this.index = props.index
        this.init(props)
        this.allEvents = []
        this.events = []
    }

    init(props) {
        const { post, rooms, calendarItem } = props
        if (post.defenses[this.index] === undefined)
            this.defense.room = rooms[0].room_id
        else this.defense.room = post.defenses[this.index].room
        this.defense.type =
            calendarItem.request_defense[this.index].action.action_id
    }

    componentDidMount() {
        this.fullCalendarInit()
        const { dispatch, post, calendarItem } = this.props
        dispatch(
            getDefenseEvent(
                calendarItem,
                this.defense,
                post.teachers,
                events => {
                    this.allEvents = events
                    this.reEvent(this.defense.room)
                }
            )
        )
    }

    reEvent(room) {
        let events = []
        this.allEvents.map(event => {
            if (event.type === 'defense' || event.type === 'current') {
                if (event.room === room) {
                    events.push(event)
                } else {
                    if (event.teacher.length !== 0) {
                        events.push(
                            Object.assign({}, event, {
                                ...event,
                                type: 'teacher',
                                className: 'selected-teacher'
                            })
                        )
                    }
                }
            }
        })
        this.events = events
        $(this.calendar).fullCalendar('unselect')
        $(this.calendar).fullCalendar('removeEvents')
        $(this.calendar).fullCalendar('addEventSource', this.events)
        $(this.calendar).fullCalendar('rerenderEvents')
    }

    unselect() {
        $(this.calendar).fullCalendar('unselect')
        const index = this.allEvents.findIndex(
            event => event.type === 'current'
        )
        if (index !== -1) this.allEvents.splice(index, 1)
    }

    fullCalendarInit() {
        const { calendarItem, lang } = this.props
        const defaultStart =
            calendarItem.request_defense[this.index].calendar_item_date_start
        const defaultEnd =
            calendarItem.request_defense[this.index].calendar_item_date_end
        $(this.calendar).fullCalendar({
            themeSystem: 'bootstrap3',
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'month,agendaDay'
            },
            locale: lang.lang,
            eventOverlap: false,
            slotEventOverlap: false,
            defaultDate: defaultStart === null ? new Date() : defaultStart,
            selectable: true,
            selectHelper: true,
            allDaySlot: false,
            minTime: '08:00',
            maxTime: '20:00',
            slotLabelFormat: 'HH:mm',
            events: this.events,
            selectLongPressDelay: 50,
            validRange: {
                start: defaultStart === null ? new Date() : defaultStart,
                end:
                    defaultEnd === null
                        ? null
                        : new Date(defaultEnd).setDate(
                              new Date(defaultEnd).getDate() + 1
                          )
            },
            eventRender: (event, element) => {
                if (event.title) {
                    const { rooms } = this.props
                    const view = $(this.calendar).fullCalendar('getView')
                    const start = moment(new Date(event.start._i)).format('LT')
                    const end = moment(new Date(event.end._i)).format('LT')
                    const roomIndex = rooms.findIndex(
                        room => room.room_id === event.room
                    )
                    const room =
                        roomIndex === -1 ? '' : rooms[roomIndex].room_name
                    if (view.type === 'agendaDay') {
                        if (
                            event.type === 'defense' ||
                            event.type === 'current'
                        ) {
                            element.context.innerHTML = ReactDOMServer.renderToString(
                                <div class='vertical-middle'>
                                    <div>
                                        {`${event.title} เวลา ${start} - ${end} ที่ ${room}`}
                                    </div>
                                </div>
                            )
                        } else if (event.type === 'teacher') {
                            element.context.innerHTML = ReactDOMServer.renderToString(
                                <div class='vertical-middle'>
                                    <div>
                                        {`${event.teacher.map(
                                            teacher => teacher
                                        )} เป็นกรรมการ${
                                            event.title
                                        }ในช่วงเวลา ${start} - ${end} ที่ ${room}`}
                                    </div>
                                </div>
                            )
                        }
                    } else if (view.type === 'month') {
                        element.context.innerHTML = `${start} - ${end}`
                    }
                } else {
                    element.context.innerHTML = ''
                }
            },
            select: (start, end) => {
                //IF SELECT TIME NOT DATE
                const { calendarItem } = this.props
                if (start._i.length !== undefined) {
                    let overlap = $(this.calendar).fullCalendar(
                        'clientEvents',
                        ev => {
                            if (ev.type !== 'current')
                                return (
                                    Math.round(ev.start) / 1000 <
                                        Math.round(end) / 1000 &&
                                    Math.round(ev.end) > Math.round(start)
                                )
                        }
                    )
                    start = moment(
                        new Date(
                            start._i[0],
                            start._i[1],
                            start._i[2],
                            start._i[3],
                            start._i[4],
                            start._i[5],
                            start._i[6]
                        )
                    )
                    end = moment(
                        new Date(
                            end._i[0],
                            end._i[1],
                            end._i[2],
                            end._i[3],
                            end._i[4],
                            end._i[5],
                            end._i[6]
                        )
                    )

                    //IF OVERLAP
                    if (overlap.length) {
                        $(this.calendar).fullCalendar('unselect')
                    } else {
                        this.unselect()
                        const event = {
                            title:
                                calendarItem.request_defense[this.index].action
                                    .action_name,
                            start: start.format('YYYY-MM-DDTHH:mm:ss'),
                            end: end.format('YYYY-MM-DDTHH:mm:ss'),
                            type: 'current',
                            teacher: [],
                            room: this.defense.room,
                            className: 'selected-current'
                        }
                        this.allEvents.push(event)
                        this.reEvent(this.defense.room)
                    }
                } else {
                    $(this.calendar).fullCalendar('changeView', 'agendaDay')
                    $(this.calendar).fullCalendar('gotoDate', start)
                }
            }
        })
    }

    roomChange(room) {
        room = parseInt(room)
        if (room !== this.defense.room) {
            this.defense.room = room
            this.reEvent(room)
        }
    }

    validate() {
        const index = this.events.findIndex(event => event.type === 'current')
        if (index === -1) {
            alert('date')
            return false
        }
        const event = this.events[index]
        this.setPost(event)
    }

    render() {
        const { lang, calendarItem, index, rooms, defense } = this.props
        return (
            <Row type='flex' justify='center' class='margin-bottom-16'>
                <Col span={24} class='text-center margin-bottom-16'>
                    <h3>
                        {calendarItem.request_defense[index].action.action_name}
                    </h3>
                </Col>
                <Col span={24} class='text-center margin-bottom-16'>
                    <select
                        class='select'
                        defaultValue={rooms[0].room_id}
                        onChange={ev => this.roomChange(ev.target.value)}
                    >
                        {rooms.map((room, index) => (
                            <option key={index} value={room.room_id}>
                                {room.room_name}
                            </option>
                        ))}
                    </select>
                </Col>
                <Col span={24}>
                    <div
                        ref={elm => {
                            this.calendar = elm
                        }}
                    />
                </Col>
            </Row>
        )
    }
}
