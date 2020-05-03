import React from 'react'
import {connect} from 'react-redux'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Tag from 'antd/lib/tag'
import {URL} from '../../config'

@connect((store) => {
    return {
        lang: store.language.data
    }
})
export default class RequestListEach extends React.Component {
    render() {
        const {calendarItem, lang} = this.props
        return (
            <tr>
                <td>
                    <Row class='table-row' type='flex'>
                        <Col class='text-center table-col' sm={8} span={24}>
                            {calendarItem.action.action_name}
                        </Col>
                        <Col class='text-center table-col' sm={8} span={24}>
                            {`${moment(new Date(calendarItem.calendar_item_date_start)).format("LL")} ${lang.request_list_each.to} ${moment(new Date(calendarItem.calendar_item_date_end)).format("LL")}`}
                        </Col>
                        <Col class='text-center table-col' sm={8} span={24}>
                            {
                                calendarItem.calendar_item_open.open ?
                                    <Tag onClick={() => {
                                        window.location = URL.REQUEST.REQUEST_ADD.MAIN.LINK(calendarItem)
                                    }} class='clickable tag-success'>
                                        {calendarItem.calendar_item_added ? lang.request_list_each.edit : lang.request_list_each.add}
                                    </Tag> :
                                    <Tag class='tag-error'>
                                        {calendarItem.calendar_item_open.status.status_name}
                                    </Tag>
                            }
                        </Col>
                    </Row>
                </td>
            </tr>
        )
    }
}