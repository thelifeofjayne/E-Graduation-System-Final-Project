import React from 'react'
import { connect } from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import InputNumber from 'antd/lib/input-number'
import Input from 'antd/lib/input'
import Icon from 'antd/lib/icon'
import Button from 'antd/lib/button'
import DefenseResultEach from './defense-result-each'

const { TextArea } = Input

@connect(store => {
    return {
        lang: store.language.data
    }
})
export default class DefenseResult extends React.Component {
    render() {
        const { lang, userRequest, index, notYet } = this.props
        return userRequest.defenses.map((defense, index_) => (
            <DefenseResultEach
                key={index_}
                mainIndex={index}
                index={index_}
                notYet={notYet}
                userRequest={userRequest}
                defense={defense}
            />
        ))
    }
}
