import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Card from 'antd/lib/card'
import Button from './button'
import { insert } from './../../../actions/request/requestAdd'
import { URL } from '../../../config'

@connect(store => {
    return {
        lang: store.language.data,
        post: store.requestAdd.post,
        positions: store.requestAdd.positions,
        teachers: store.requestAdd.teachers,
        calendarItem: store.requestAdd.calendarItem,
        rooms: store.requestAdd.rooms,
        config: store.main.config
    }
})
export default class Summary extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            inserting: false
        }
    }

    validate() {
        this.insert()
        return true
    }

    insert() {
        const { post, calendarItem, dispatch, config } = this.props
        const { inserting } = this.state
        this.setState({ inserting: true })
        dispatch(
            insert(post, calendarItem, response => {
                this.setState({ inserting: false })
                window.location =
                    calendarItem.action.action_type_id ===
                    config.ACTION_INIT_TYPE
                        ? URL.CALENDAR.CALENDAR_INIT.MAIN.LINK
                        : URL.REQUEST.REQUEST_LIST.MAIN.LINK
            })
        )
    }

    sort(first, second) {
        return first.position - second.position
    }

    render() {
        const {
            lang,
            post,
            positions,
            teachers,
            calendarItem,
            rooms
        } = this.props
        const { inserting } = this.state
        return [
            <Row key='step' class='step' type='flex' justify='center'>
                <Col class='text-center' sm={18} span={24}>
                    <h4 style={{ width: '100%' }}>
                        {lang.summary.teacher_list}
                    </h4>
                </Col>
                <Col class='text-center' sm={18} span={24}>
                    <Card>
                        {post.teachers.length === 0 ? (
                            <Col class='text-center' span={24}>
                                {lang.nodata}
                            </Col>
                        ) : (
                            post.teachers
                                .sort(this.sort)
                                .map((teacher, index) => {
                                    const person =
                                        teachers[
                                            teachers.findIndex(
                                                _teacher =>
                                                    _teacher.id ===
                                                    teacher.teacher
                                            )
                                        ]
                                    const position =
                                        positions[
                                            positions.findIndex(
                                                position =>
                                                    position.position_id ===
                                                    teacher.position
                                            )
                                        ]
                                    return [
                                        <Col
                                            class='text-center'
                                            key={`t${index}`}
                                            span={12}
                                        >
                                            {`${person.prefix} ${person.person_fname} ${person.person_lname}`}
                                        </Col>,
                                        <Col
                                            class='text-center'
                                            key={`p${index}`}
                                            span={12}
                                        >
                                            {position.position_name}
                                        </Col>
                                    ]
                                })
                        )}
                    </Card>
                </Col>
                {calendarItem.request_defense.map((defense, index) => [
                    <Col
                        key={`def${index}`}
                        class='text-center'
                        sm={18}
                        span={24}
                    >
                        <h4>{defense.action.action_name}</h4>
                    </Col>,
                    post.defenses.length === 0 ? (
                        <Col key={index} class='text-center' sm={18} span={24}>
                            <Card>{lang.nodata}</Card>
                        </Col>
                    ) : (
                        <Col key={index} class='text-center' sm={18} span={24}>
                            <Card>
                                <Col class='text-center' span={12}>
                                    {lang.summary.date}
                                </Col>
                                <Col class='text-center' span={12}>
                                    {`${moment(
                                        new Date(post.defenses[index].date)
                                    ).format('LL')}`}
                                </Col>
                                <Col class='text-center' span={12}>
                                    {lang.summary.time}
                                </Col>
                                <Col class='text-center' span={12}>
                                    {`${moment(
                                        new Date(
                                            `${post.defenses[index].date}T${post.defenses[index].start}`
                                        )
                                    ).format('LT')} - ${moment(
                                        new Date(
                                            `${post.defenses[index].date}T${post.defenses[index].end}`
                                        )
                                    ).format('LT')}`}
                                </Col>
                                <Col class='text-center' span={12}>
                                    {lang.summary.room}
                                </Col>
                                <Col class='text-center' span={12}>
                                    {
                                        rooms.filter(
                                            room =>
                                                room.room_id ===
                                                post.defenses[index].room
                                        )[0].room_name
                                    }
                                </Col>
                            </Card>
                        </Col>
                    )
                ])}
            </Row>,
            <Button
                key='btn'
                inserting={inserting}
                validate={() => this.validate()}
            />
        ]
    }
}
