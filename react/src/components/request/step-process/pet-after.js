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
        status: store.requestData.status
    }
})
export default class PetBefore extends React.Component {
    render() {
        const { lang, userRequest, status, index, notYet } = this.props
        return (
            <div style={{ marginTop: 8 }}>
                {userRequest.post_request_document.map(
                    (requestDocument, index_) => {
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
                    }
                )}
            </div>
        )
    }
}
