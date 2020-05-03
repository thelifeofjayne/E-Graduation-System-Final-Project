import React from 'react'
import { connect } from 'react-redux'
import Tag from 'antd/lib/tag'
import {
    toggledefenseDocument,
    updateUserRequest
} from '../../../actions/request/requestData'
import Badge from 'antd/lib/badge'
import Icon from 'antd/lib/icon'
import { URL } from './../../../config'

@connect(store => {
    return {
        lang: store.language.data,
        status: store.requestData.status,
        config: store.main.config,
        currentUser: store.main.currentUser
    }
})
export default class DocEach extends React.Component {
    constructor(props) {
        super(props)
        const {
            defenseDocument,
            userRequest,
            currentUser,
            config,
            notYet
        } = props
        this.input = null
        this.state = {
            path: defenseDocument.defense_document_path,
            file: null,
            disabled: false,
            edit: false
        }
    }

    upload() {
        const { dispatch, userRequest, defenseDocument, mainIndex } = this.props
        const { file } = this.state
        if (file === null) {
            alert('NULL LUL')
            return false
        }
        this.setState({ disabled: true })
        dispatch(
            toggledefenseDocument(
                file,
                userRequest,
                defenseDocument,
                response => {
                    dispatch(updateUserRequest(mainIndex, response))
                    this.setState({ disabled: false, edit: false })
                }
            )
        )
    }

    fileChange(file) {
        if (file !== undefined) this.setState({ file })
    }

    edit(edit) {
        this.setState({ edit })
    }

    render() {
        const {
            lang,
            userRequest,
            status,
            defenseDocument,
            currentUser,
            config,
            notYet
        } = this.props
        const { file, disabled, edit, path } = this.state
        const isStaff = currentUser.user_type_id === config.PERSON_STAFF_TYPE
        const isOwner = currentUser.id === userRequest.student.id
        const editor =
            (isStaff || isOwner) &&
            !notYet &&
            defenseDocument.defense_document_status_id !==
                config.DOC_STATUS_NO_NEED
        const status_ =
            status[
                status.findIndex(
                    status =>
                        status.status_id ===
                        defenseDocument.defense_document_status_id
                )
            ]
        return (
            <div style={{ marginBottom: 8 }}>
                {defenseDocument.document.document_name}
                {defenseDocument.defense_document_path === null ||
                edit ? null : (
                    <a
                        href={`${URL.BASE}/${defenseDocument.defense_document_path}`}
                        target='_blank'
                    >
                        <Tag class='clickable tag-default'>
                            <Icon type='file-pdf' /> {lang.doc_each.open}
                        </Tag>
                    </a>
                )}
                <Tag class={`tag-${status_.status_label}`}>
                    {status_.status_name}
                </Tag>
                {edit && editor ? (
                    <span>
                        <input
                            onChange={ev => {
                                this.fileChange(ev.target.files[0])
                                ev.target.value = null
                            }}
                            ref={ref => (this.input = ref)}
                            class='hidden'
                            type='file'
                            accept='Application/pdf'
                        />
                        <Tag
                            onClick={() => this.input.click()}
                            class={`clickable tag-empty ${
                                file === null ? null : 'hidden'
                            }`}
                        >
                            {lang.doc_each.to_upload}
                        </Tag>
                        <span class={`${file === null ? 'hidden' : null}`}>
                            <Tag class='tag-default'>
                                {file === null ? null : file.name}
                            </Tag>
                            <Badge
                                onClick={() => this.fileChange(null)}
                                style={{
                                    transform: 'scale(0.7)',
                                    marginLeft: -8,
                                    top: -20,
                                    lineHeight: '17.5px'
                                }}
                                class={`clickable`}
                                count='x'
                            >
                                <span />
                            </Badge>
                        </span>
                        {file === null ? null : (
                            <Tag
                                onClick={() => this.upload()}
                                class='clickable tag-success'
                            >
                                {lang.doc_each.upload}
                            </Tag>
                        )}
                    </span>
                ) : !editor ? null : (
                    <Tag
                        onClick={() => this.edit(true)}
                        class='clickable tag-default'
                    >
                        {lang.doc_each.edit}
                    </Tag>
                )}
            </div>
        )
    }
}
