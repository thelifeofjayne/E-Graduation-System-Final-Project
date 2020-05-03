import React from 'react'
import { connect } from 'react-redux'
import Button from '../step/button'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../../loading'
import { getDefenseEvent, setPost } from '../../../actions/request/requestAdd'
import ReactDOMServer from 'react-dom/server'
import Test from '../../test'
import DefenseEach from './defense-each'

@connect(store => {
    return {
        lang: store.language.data,
        calendarItem: store.requestAdd.calendarItem,
        post: store.requestAdd.post
    }
})
export default class Defense extends React.Component {
    constructor() {
        super()
        this.each = []
    }

    validate() {
        const { dispatch, post } = this.props
        let defenses = []
        const validates = this.each.map(defenseEach => {
            const index = defenseEach.events.findIndex(
                event => event.type === 'current'
            )
            if (index === -1) {
                alert('date')
            } else {
                const event = defenseEach.events[index]
                defenseEach.defense = {
                    ...defenseEach.defense,
                    date: moment(new Date(event.start)).format('YYYY-MM-DD'),
                    start: moment(new Date(event.start)).format('HH:mm'),
                    end: moment(new Date(event.end)).format('HH:mm')
                }
                defenses.push(defenseEach.defense)
            }
            return index !== -1
        })
        if (validates) {
            const _post = Object.assign({}, post, {
                ...post,
                defenses
            })
            dispatch(setPost(_post))
        }
        return validates.every(validate => validate)
    }

    render() {
        const { lang, calendarItem } = this.props
        return [
            <Row key='step' class='step'>
                {calendarItem.request_defense.map((defense, index) => (
                    <DefenseEach
                        ref={ref =>
                            ref === null
                                ? []
                                : this.each.push(ref.getWrappedInstance())
                        }
                        key={index}
                        index={index}
                        defense={defense}
                    />
                ))}
            </Row>,
            <Button key='btn' validate={() => this.validate()} />
        ]
    }
}
