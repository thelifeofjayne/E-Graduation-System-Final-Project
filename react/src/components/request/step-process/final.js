import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../../loading'
import Button from '../step/button'

@connect(store => {
    return {
        lang: store.language.data,
        calendarItem: store.requestAdd.calendarItem
    }
})
export default class Final extends React.Component {
    render() {
        const { lang, calendarItem } = this.props
        return <div>{lang.final.final}</div>
    }
}
