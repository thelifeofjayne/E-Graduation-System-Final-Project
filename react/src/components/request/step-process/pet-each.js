import React from 'react'
import { connect } from 'react-redux'
import Tag from 'antd/lib/tag'
import Checkbox from 'antd/lib/checkbox'
import {
    togglePetition,
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
export default class PetEach extends React.Component {
    constructor(props) {
        super(props)
        const {
            requestDocument,
            userRequest,
            config,
            notYet,
            currentUser
        } = props
        this.state = {
            disabled: false
        }
    }

    toggleCheck(checked) {
        const { dispatch, userRequest, requestDocument, mainIndex } = this.props
        this.setState({ disabled: true })
        dispatch(
            togglePetition(checked, userRequest, requestDocument, response => {
                dispatch(updateUserRequest(mainIndex, response))
                this.setState({ disabled: false })
            })
        )
    }

    render() {
        const {
            lang,
            userRequest,
            status,
            requestDocument,
            notYet,
            config,
            currentUser
        } = this.props
        const { disabled } = this.state
        const isStaff = currentUser.user_type_id === config.PERSON_STAFF_TYPE
        const editor =
            isStaff &&
            !notYet &&
            requestDocument.request_document_status_id !==
                config.DOC_STATUS_NO_NEED
        const status_ =
            status[
                status.findIndex(
                    status =>
                        status.status_id ===
                        requestDocument.request_document_status_id
                )
            ]
        const checked =
            requestDocument.request_document_status_id !==
            config.DOC_STATUS_NOT_SUBMITTED
        return (
            <div style={{ marginBottom: 8 }}>
                {requestDocument.document.document_name}
                <Tag class={`tag-${status_.status_label}`}>
                    {status_.status_name}
                </Tag>
                {!editor ? null : (
                    <Checkbox
                        class='margin-left-8'
                        checked={checked}
                        disabled={disabled}
                        onChange={ev => this.toggleCheck(ev.target.checked)}
                    />
                )}
            </div>
        )
    }
}
