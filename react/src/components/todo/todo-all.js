import React from 'react'
import { connect } from 'react-redux'
import { URL } from './../../config'
import Tag from 'antd/lib/tag'
import Row from 'antd/lib/row'
import Col from 'antd/lib/col'
import Loading from '../loading'
import { getTodo } from '../../actions/todo/todo-all'
import { setHeader } from '../../actions/main'

@connect(store => {
    return {
        config: store.main.config,
        lang: store.language.data,
        todos: store.todoAll.todos
    }
})
export default class TodoAll extends React.Component {
    componentDidMount() {
        const { dispatch, lang } = this.props
        dispatch(setHeader(lang.todo_all.head))
        dispatch(getTodo())
    }

    render() {
        const { todos, lang } = this.props
        return todos === null ? (
            <Loading />
        ) : (
            <Row>
                <Col span={24}>
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>
                                    <Row class='table-row' type='flex'>
                                        <Col
                                            class='text-center table-col'
                                            sm={16}
                                            span={24}
                                        >
                                            {lang.todo_all.todo}
                                        </Col>
                                        <Col
                                            class='text-center table-col'
                                            sm={8}
                                            span={24}
                                        >
                                            {lang.todo_all.status}
                                        </Col>
                                    </Row>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {todos.map((todo, index) => (
                                <tr key={index}>
                                    <td>
                                        <Row class='table-row' type='flex'>
                                            <Col
                                                class='text-center table-col'
                                                sm={16}
                                                span={24}
                                            >
                                                <div>{todo.todo_name}</div>
                                            </Col>
                                            <Col
                                                class='text-center table-col'
                                                sm={8}
                                                span={24}
                                            >
                                                <Tag
                                                    class={`tag-${
                                                        todo.pass
                                                            ? 'success'
                                                            : 'error'
                                                    }`}
                                                >
                                                    {todo.pass
                                                        ? lang.todo_all.pass
                                                        : lang.todo_all.nope}
                                                </Tag>
                                            </Col>
                                        </Row>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </Col>
            </Row>
        )
    }
}
