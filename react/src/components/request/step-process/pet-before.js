import React from 'react'
import { connect } from 'react-redux'
import Tag from 'antd/lib/tag'
import Checkbox from 'antd/lib/checkbox'
import PetEach from './pet-each'
import {
    toggleFee,
    updateUserRequest
} from '../../../actions/request/requestData'

@connect(store => {
    return {
        lang: store.language.data,
        status: store.requestData.status,
        config: store.main.config,
        currentUser: store.main.currentUser
    }
})
export default class PetBefore extends React.Component {
    constructor(props) {
        super(props)
        const { userRequest } = props
        this.PAID = 8
        this.NOT_PAY_STATUS = 9
        this.DONT_NEED_TO_PAY = 10
        this.state = {
            disabled: false
        }
    }

    toggleFee(checked) {
        const { dispatch, userRequest, requestDocument, index } = this.props
        this.setState({ disabled: true })
        dispatch(
            toggleFee(checked, userRequest, response => {
                dispatch(updateUserRequest(index, response))
                this.setState({ disabled: false })
            })
        )
        this.setState({ checked })
    }

    render() {
        const {
            lang,
            userRequest,
            status,
            index,
            notYet,
            currentUser,
            config
        } = this.props
        const { disabled } = this.state
        const checked = userRequest.request_fee.fee_status_id === this.PAID
        const isStaff = currentUser.user_type_id === config.PERSON_STAFF_TYPE
        const editor =
            isStaff &&
            userRequest.request_fee.fee_status_id !== this.DONT_NEED_TO_PAY
        const fee_status =
            status[
                status.findIndex(
                    status =>
                        status.status_id ===
                        userRequest.request_fee.fee_status_id
                )
            ]
        return (
            <div style={{ marginTop: 8 }}>
                <div style={{ marginBottom: 8 }}>
                    {userRequest.request_fee.amount === 0 ? null : (
                        <label style={{ marginLeft: 8 }}>
                            {lang.pet_before.amount}:{' '}
                            {userRequest.request_fee.amount}
                        </label>
                    )}
                    <Tag
                        style={{ marginLeft: 8 }}
                        class={`tag-${fee_status.status_label}`}
                    >
                        {fee_status.status_name}
                    </Tag>
                    {!editor ? null : (
                        <Checkbox
                            style={{ marginLeft: 8 }}
                            checked={checked}
                            disabled={disabled}
                            onChange={ev => this.toggleFee(ev.target.checked)}
                        />
                    )}
                </div>
                {userRequest.request_document.map((requestDocument, index_) => {
                    const status_ =
                        status[
                            status.findIndex(
                                status =>
                                    status.status_id ===
                                    requestDocument.request_document_status_id
                            )
                        ]
                    return (
                        <PetEach
                            key={index_}
                            index={index_}
                            notYet={notYet}
                            mainIndex={index}
                            userRequest={userRequest}
                            requestDocument={requestDocument}
                        />
                    )
                })}
            </div>
        )
    }
}
