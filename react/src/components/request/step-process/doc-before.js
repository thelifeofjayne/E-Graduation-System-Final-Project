import React from 'react'
import { connect } from 'react-redux'
import Tag from 'antd/lib/tag'
import Checkbox from 'antd/lib/checkbox'
import DocEach from './doc-each'

@connect(store => {
    return {
        lang: store.language.data,
        status: store.requestData.status
    }
})
export default class DocBefore extends React.Component {
    render() {
        const { lang, userRequest, status, index, notYet } = this.props
        return (
            <div style={{ marginTop: 8 }}>
                {userRequest.defense_document.map((defenseDocument, index_) => {
                    const status_ =
                        status[
                            status.findIndex(
                                status =>
                                    status.status_id ===
                                    defenseDocument.defense_document_status_id
                            )
                        ]
                    return (
                        <DocEach
                            key={index_}
                            index={index_}
                            mainIndex={index}
                            notYet={notYet}
                            userRequest={userRequest}
                            defenseDocument={defenseDocument}
                        />
                    )
                })}
            </div>
        )
    }
}
